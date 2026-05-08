<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use LoveyCom\CashFree\PaymentGateway\Order;
use Session;
use App\Models\WalletAmout;
use App\Models\Subscription;
use App\Models\SubscriptionHistory;
use App\Models\Adposting;
use App\Models\Customer;
use App\Models\SubscriptionOrder;

class PaymentController extends Controller
{
   public function orderProcess(Request $request)
    {
        
        $customer = Customer::where('email',$request->input('email'))->first();
        
        $totalAds = Adposting::where('user_id',$customer->id)->where('delete_status',0)->count();
    	      
        $subscriptionhistory = SubscriptionHistory::where('user_id',$customer->id)->where('remaining_ads','>',$totalAds)->whereDate('subscription_expiry','>=',date('Y-m-d'))->exists();
        /**if($subscriptionhistory){
                
                return response()->json(['error' => 'Ads are already available to publish in your active subscription. Please use all the ads in the bucket first.'], 500);
                
        }***/
        try {
            // $order = new Order();
            // $od["orderId"] = "ORDER-" . rand(1000, 9999);
            // $od["orderAmount"] = $request->input('price');
            // $od["orderNote"] = $request->input('description');
            // $od["customerPhone"] = $request->input('phone');
            // $od["customerName"] = $request->input('name');
            // $od["customerEmail"] = $request->input('email');
            // $od["orderCurrency"] = "INR";
            // $od["businessName"] = "WelcomePost";
            // $od["returnUrl"] = route('order.status');
            // $od["notifyUrl"] = route('purchase-subscription');
            
            
            //**************new
            
            
            $url = env('CASHFREE_URL');

          $headers = array(
               "Content-Type: application/json",
               "x-api-version: 2023-08-01",
               "x-client-id: ".env('CASHFREE_API_KEY'),
               "x-client-secret: ".env('CASHFREE_API_SECRET')
          );

            $orderid =  "ORDER-" . rand(1000, 9999);
            $paymentSessionId='';
          $data = json_encode([
               'order_id' => $orderid,
               'order_amount' => number_format($request->input('price'),2),
               "order_currency" => "INR",
               "customer_details" => [
                    "customer_id" => 'customer_'.rand(1000,9999),
                    "customer_name" => $request->input('name'),
                    "customer_email" => $request->input('email'),
                    "customer_phone" => "+91".$request->input('phone'),
               ],
               "order_meta" => [
                    "return_url" => url('/').'/order/status/?order_id={order_id}',
                    'notify_url' => route('purchase-subscription'),
                    'payment_methods'=>'cc, upi'
                    
               ]
          ]);

          $curl = curl_init($url);

          curl_setopt($curl, CURLOPT_URL, $url);
          curl_setopt($curl, CURLOPT_POST, true);
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
          curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

          $resp = curl_exec($curl);

          curl_close($curl);
          
          $resData=json_decode($resp);
          
          if(isset($resData->cf_order_id) && $resData->cf_order_id !='')
          {
    
            $cf_order_id=$resData->cf_order_id;
            $order_id=$resData->order_id;
            $payment_session_id=$resData->payment_session_id;
            $paymentSessionId=$payment_session_id;
            
            
            
            
            $customer = Customer::where('email',$request->input('email'))->first();
            $sub = Subscription::findOrFail($request->subscription_id);
            $package_validity 	= $sub->package_validity;
            $no 	            = explode(" ",$package_validity);	 
    		$dates 	            = date("d-m-Y");	
    		$date   	        = date_create($dates);
    		date_add($date,date_interval_create_from_date_string($no[0]."days"));
    		$subscription_expiry = date_format($date,"Y-m-d");
    		Session::put('total_wout_gst', $request->total_wout_gst);
            $userprofile					= new SubscriptionOrder;
		    $userprofile->user_id 					= $customer->id;
			$userprofile->subscription_id 			= $sub->id;
			$userprofile->transaction_id			= $order_id;
			$userprofile->payment_method			= 'online';
			$userprofile->payment_status			= 'Pending';
			$userprofile->used_ads					= '0';
			$userprofile->remaining_ads				= $sub->no_of_ads;
			$userprofile->subscription_expiry 	=   $subscription_expiry;
			$userprofile->subscription_validity		= $package_validity;
			$userprofile->category_id   			= $sub->category_id;
			$userprofile->delete_status				= '0';
			$userprofile->status 					= '0';
			if(isset($request->remaining_wallet)){
			    $userprofile->remaining_balance = $request->remaining_wallet;
			}
			$userprofile->payment_type              = $request->iswelcome;
			$userprofile->save();
			
			
            // Call the create method
            //$order->create($od);
            //$link = $order->getLink($od['orderId']);
            // Return the payment link in JSON format
            //return response()->json(['paymentLink' => $link->paymentLink]);
            
            return response()->json(['paymentLink' => $paymentSessionId]);
          }
          else{
              return response()->json(['error' => $resp], 500);
        }
        
       
            
        } catch (\Exception $e) {
            // Log the error
            \Log::error($e);
            
            // Handle the error gracefully
            return response()->json(['error' => 'An error occurred while processing your order. Please try again later. '.$e], 500);
        }
    }
    
    public function orderStatus(Request $request){
        
        if(isset($_GET['order_id']) && $_GET['order_id'] !='')
        {
            $orderId = $_GET['order_id'];
            
            $headers = array(
                   "Content-Type: application/json",
                   "x-api-version: 2023-08-01",
                    "x-client-id: ".env('CASHFREE_API_KEY'),
                    "x-client-secret: ".env('CASHFREE_API_SECRET')
              );
              
              
            $url ='https://api.cashfree.com/pg/orders/'.$orderId.'/payments';
            $curl = curl_init($url);
            
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);    
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    
            $resp = curl_exec($curl);
            
            if (curl_errno($curl)) {
                $error_msg = curl_error($curl);
            }
            
            curl_close($curl);
            if(!isset( $error_msg))
            {
                $result = json_decode($resp);
                
                if(!empty($result)){
                $order = SubscriptionOrder::where('transaction_id', $orderId)->first();
        
                $user = Customer::findOrFail($order->user_id);
                
                if ($order) {
                    
                    
                    if($result[0]->payment_status=='SUCCESS'){
                        $request->session()->put('id',$order->user_id);
                        $order->payment_status = 'Completed';
                        $order->save();
                        $cashfree = null;
                        $totalWithoutGst = Session::get('total_wout_gst');
                        if($order->payment_type==0){
                            $user->wallet_amount = $order->remaining_balance ?? $user->wallet_amount;
                            $user->save();
                            $cashfree = 1;
                        }else{
                            $user->wallet_bonus = $order->remaining_balance ?? $user->wallet_bonus;
                            $user->save();
                            $cashfree = 2;
                        }
                        return view('website.temprory-redireaction')->with(['id' => $order->subscription_id,'wallet_remaining' => 10,'cashfree' => $cashfree,'payment_id'=>$orderId,'total_subscription'=> $result[0]->payment_amount,'total_wout_gst'=>$totalWithoutGst??null]);
                    }
                    if($result[0]->payment_status=='FAILED'){
                        $request->session()->put('id',$order->user_id);
                        $order->payment_status = 'Failed';
                        $order->save();
                        return redirect()->route('purchase-subscription')->with('error','Transaction failed.');
                    }
                }
                return redirect()->route('login')->with('error', 'Invalid order');
                }
                else
                {
                    $request->session()->put('id',$order->user_id);
                    $order->payment_status = 'Cancelled';
                    $order->save();
                    return redirect()->route('purchase-subscription')->with('error','User cancelled the payment.');
                    
                    
                }
            }
            else
            {
                return redirect()->route('login')->with('error', 'Invalid order');
            }
        }
        else
        {
            return redirect()->route('login')->with('error', 'Order not found');
        }
            
        
    }
}
