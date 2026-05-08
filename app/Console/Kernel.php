<?php

namespace App\Console;
use App\Models\Counter;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\SubscriptionHistory;
use App\Models\Customer;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use App\Traits\FCMNotifications;
use App\Models\Customer_child;
use App\Models\Adposting;
use App\Models\ChildHistory;
use Illuminate\Support\Facades\Log;
use App\Models\DefaultNotification;

class Kernel extends ConsoleKernel
{
    use FCMNotifications;
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule 
     * @return void
     */
    protected function schedule(Schedule $schedule)
    { 
        
        // echo date("H:i",strtotime(now()));
        // $schedule->command('inspire')->hourly();
		$schedule->call(function () {
		    
		    $currentdate    =  date("d-m-Y");	
		    $update         =  DB::table('ads_postings')->where('ad_expiry',$currentdate)->update(
		                        array(
		                            'status'=>'3'
		                        ));
		
		    $update = DB::table('subscription_orders')->where('subscription_expiry',$currentdate)->update(
		                array(
		                    'status'=>'1'
		                ));
		
		    $subscription_orders = DB::table('subscription_orders')->where('subscription_expiry',$currentdate)->get();
			foreach ($subscription_orders as $row)
			{
				$user_id                = $row->user_id;
				$category_id            = $row->category_id;
				$ads_postings_exists    = DB::table('ads_postings')->where('user_id',$user_id)->where('category_id',$category_id)->where('active_status','0')->exists();
				
				if($ads_postings_exists)
				{
					$update = DB::table('ads_postings')->where('user_id',$user_id)->where('category_id',$category_id)->where('active_status','0')->update(
				            array(
				                'status'=>'3'
				            ));
				}
			}
		
		    /*---------- Cron counter ----------*/
		    $result         = DB::table('cron')->where('id','1')->get();
		    $result_counter = $result[0]->counter;
		
		    $update = DB::table('cron')->where('id','1')->update(
		                array(
		                    'counter'=>$result_counter
		            ));
		
		    if($result_counter >= 5)
		    {
			    $update = DB::table('cron')->where('id','1')->update(
		                array(
		                    'counter'=>'0'
		                ));
		    }
		    /*---------- Cron counter ----------*/
             
        })->dailyAt('12:05');
        
        $schedule->call(function(){
          $this->testuser();
        })->everyMinute();
        
        $schedule->command('deleteadimage')->dailyAt('12:05');
        $schedule->command('expireads')->everyMinute();
    }
    
    public function sendNotification($title, $body, $tokens, $image = null)
    {
        $notification = [
            'title' => $title,
            'body' => $body,
            'image' => $image, // Add the image path to the notification payload if provided
        ];
        
        $credentialsPath = config('services.firebase.credentials');
        $factory = (new Factory)->withServiceAccount(base_path($credentialsPath));
        $messaging = $factory->createMessaging();

        $message = CloudMessage::new()
            ->withNotification(Notification::fromArray($notification));

        try {
            $report = $messaging->sendMulticast($message, $tokens);
            $successful = $report->successes()->count();
            $failed = $report->failures()->count();

            return [
                'success' => true,
                'message' => 'Notification sent successfully',
                'details' => [
                    'successful' => $successful,
                    'failed' => $failed,
                ],
            ];
        } catch (FirebaseException $e) {
            return [
                'success' => false,
                'message' => 'Failed to send notification: ' . $e->getMessage(),
            ];
        }
    }
    
    public function testuser(){
         try{
             DB::beginTransaction();
             $adspostings = Adposting::select('subscription_id', DB::raw('SUM(ad_view_count) as total_views_sum'))->where('delete_status',0)->where('active_status','1')->groupBy('subscription_id')->get();
             foreach($adspostings as $adsposting){
                 if(isset($adsposting->subscriptionhistory->minimum_views)&&$adsposting->subscriptionhistory->type=='Prime'){
                     Log:info($adsposting->user_id.' is a valid prime member');
                    if($adsposting->total_views_sum >= ($adsposting->subscriptionhistory->minimum_views) ){
                        SubscriptionHistory::where('id',$adsposting->subscription_id)->update(['type'=>'Premium']);
                    }
                 }
             }
             $subscriptions = SubscriptionHistory::whereDate('subscription_expiry','<', date('Y-m-d'))->where('status',0)->get();
             foreach($subscriptions as $subscription){
                $customerX = Customer::find($subscription->user_id);
                if(isset($customerX)&&$customerX->fcm_token){
                    $title = 'Subscription Expired!';
                    $body = $subscription->subscription_number.' Package Expired';
                    $image = null;
                    $response = $this->sendNotification($title, $body, $customerX->fcm_token, $image);
                }
             }
            SubscriptionHistory::whereDate('subscription_expiry','<', date('Y-m-d'))->where('status',0)->update(['type'=>'Normal','status'=>1]); 
            Customer::whereDate('reserve_expiry_at','<=', date('Y-m-d'))->update(['parent_id'=>NULL,'reserve_expiry_at'=>NULL]);
            Customer_child::whereDate('reserve_expiry_at','<=', date('Y-m-d'))->update(['status'=>'Removed','removal_date'=>date('Y-m-d')]);
           

            $adminsetting = \App\Models\Adminsettings::first();
            if ($adminsetting && $adminsetting->auto_join == "1") {
                // Logging: Admin settings found and auto_join enabled
             
                $customersp = SubscriptionHistory::with("customers")->where('join_complete', 'no')
                    ->orderByRaw("CASE WHEN type = 'Premium' THEN 1 WHEN type = 'Prime' THEN 2 ELSE 3 END")
                    ->where('auto_join', 1)
                    ->orderBy('created_at', 'ASC')
                    ->get();
            
                // Logging: Found customers for auto-joining
                \Log::info(count($customersp) . ' customers found for auto-joining.');
            
                foreach ($customersp as $customers) {
                    if ($customers->customers && $customers->customers->membership_expiry_at >= date('Y-m-d')) {
                        // Logging: Customer's membership valid
                        Log::info('Customer ' . $customers->customers->name . ' has valid membership.');
            
                        $totaljoined = $customers->total_joined;
                        if ($customers->auto_join_member != $customers->total_joined) {
                            $customersas = Customer::where('id', '!=', $customers->user_id)
                                ->whereDate('membership_expiry_at', '>=', date('Y-m-d'))
                                ->where('id',"!=",$customers->customers->parent_id)
                                ->whereNull('parent_id')
                                ->select('id','name')
                                ->orderBy('created_at', 'ASC')
                                ->whereNotNull('mobile')
                                ->limit($customers->auto_join_member - $customers->total_joined)
                                ->get();
            
                            // Logging: Customers found for auto-joining
                            Log::info(count($customersas) . ' customers found for auto-joining for customer test ' . $customers->customers->name);
                            Log::info($customers->customers->name);
                            foreach ($customersas as $customersas1) {
                                Log::info($customersas1->name);
                                    $reserve_expiry_at = (new \DateTime())->modify('+' . $adminsetting->reserve_expiry_timeline . ' days')->format('Y-m-d');
                                    Customer_child::create([
                                        'user_id' => $customers->user_id,
                                        'child_id' => $customersas1->id,
                                        'subscription_id' => $customers->id,
                                        'joining_date' => date('Y-m-d'),
                                        'reserve_expiry_at' => $reserve_expiry_at,
                                        'status' => 'Active',
                                    ]);
                                     $customerX = Customer::findOrFail($customers->user_id);
                                    if($customerX->fcm_token){
                                        $title = 'New seed added!';
                                        $body = $customersas1->member_id.' member added to your downstream.';
                                        $image = null;
                                        $response = $this->sendNotification($title, $body, $customerX->fcm_token, $image);
                                    }
                                    $event = DefaultNotification::where('event', 'new_seeding')->first();
                                    if(!empty($event))
                                    {
                                        $title = $event->title;
                                        $content = $event->content;
                                        $body = str_replace("#member_id",$customersas1->member_id, $content);
                                        $notifyArray=array(
                                            'user_id' => $customerX->id,
                                            'event_name' => $event->event,
                                            'title' => $title,
                                            'body' => $body,
                                        );
                                    
                                        $this->singleUserNotification($notifyArray);
                                    }
                
                                    // Logging: Customer child created
                                    Log::info('Customer child created for customer ' . $customers->customers->name . ' with ID ' . $customersas1->id);
                
                                    $customersas1->update(['parent_id' => $customers->user_id, 'reserve_expiry_at' => $reserve_expiry_at]);
                                    $totaljoined = $totaljoined + 1;
                            }
                        }
                        Log::info('Auto-join process completed for customer ' . $customers->customers->name . ' Total Joined ' .$totaljoined);
                        $joincomplete = $totaljoined == $customers->auto_join_member ? "yes" : "no";
                        $customers->update(['join_complete' => $joincomplete, 'total_joined' => $totaljoined, 'type' => $joincomplete == "yes" ? "Prime" : $customers->type]);
                    }
                }
            } else {
                // Logging: Admin settings not found or auto_join disabled
                Log::info('Admin settings not found or auto_join is disabled.');
            }
             
        
         
         DB::commit();
         }catch(\Exception $ex){
             DB::rollback();
             echo $ex->getMessage().'-'.$ex->getLine();
             Log::error('An error occurred in testuser function: ' . $ex->getMessage());
            Log::error('Line: ' . $ex->getLine());
         }
        //  return true;
        //  print_r($customersp);
        //  die();
     } 

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
