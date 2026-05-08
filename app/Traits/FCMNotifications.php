<?php

namespace App\Traits;
use App\Models\DefaultNotification;
use App\Models\Customer;
use App\Models\DefaultNotificationHistory;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Messaging;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Kreait\Firebase\Messaging\CloudMessage;

trait FCMNotifications
{
    public function singleUserNotification($data)
    {
        $firebase = (new Factory)
        ->withServiceAccount(config_path().'/firebase_credentials.json');

        $messaging = $firebase->createMessaging();
        if(!empty($data))
        {
            $user_id = $data['user_id'];
            $event_name = $data['event_name'];
            $title = $data['title'];
            $body = $data['body'];
            $user = Customer::findOrFail($user_id);
            
            
            if(!empty($user) && isset($user->fcm_token) && $user->fcm_token !='')
            {
                $deviceToken = $user->fcm_token;
                if($this->isValidFireBaseMessagingToken($deviceToken))
                {
                    $message = CloudMessage::withTarget('token', $deviceToken)
                    ->withNotification([
                        'title' => $title,
                        'body' => $body
                    ]);

                    try {
                        $messaging->send($message);
                        
                        DefaultNotificationHistory::create([
                            'customer_id'=>$user->id,
                            'event'=>$event_name,
                            'title'=> $title,
                            'message'=> $body,
                            'status'=> 'success',
                        ]);
                        
                    } catch (NotFound $e) {
                        //echo $e;
                    } catch (InvalidArgument $e) {
                        //echo $e;
                    }
                }
            }
        }
      
    }



    // public function MultiUserNotification($data)
    // {
    //     $firebase = (new Factory)
    //     ->withServiceAccount(config_path().'/firebase_credentials.json');

    //     $messaging = $firebase->createMessaging();
    //     if(!empty($data))
    //     {
    //         $user_id = $data['post_by'];
    //         $user = User::findOrFail($user_id);
    //         $title = $data['title'];
    //         $id = $data['id'];
    //         $slug = $data['slug'];
    //         $link = '';
    //         $category = Postcategory::with(['category' =>function ($q){
    //             $q->select("categories.id", "categories.name", "categories.slug");
    //         }])->where('post_id', $id)->select('id', 'post_id', 'category_id')->first();

    //         if(isset($category) && !empty($category) > 0)
    //         {
    //             $cslug = $category->category->slug;
    //             $link = env('APP_URL').''.$cslug.'/'.$slug.'/detail';
    //         }
            
    //         $body = $title."\n".$link;

    //         $users = User::where('id', '!=', $id)->whereNotNull('fcm_token')->get();
    //         $deviceIds = array();
            
            
    //         if(isset($users) && count($users)>0)
    //         {
    //             foreach($users as $user)
    //             {
    //                 if($user->fcm_token !='')
    //                 {
    //                     $deviceIds[]=$user->fcm_token;
    //                 }
    //             }

    //             if(!empty($deviceIds))
    //             {
    //                 $deviceToken = 'foo';//$user->fcm_token;
    //                 $message = CloudMessage::withTarget('token', $deviceToken)
    //                 ->withNotification([
    //                     'title' => 'Prayag Darpan News Published',
    //                     'body' => $body
    //                 ]);


    //                 try {
    //                     //$messaging->send($message);
    //                     foreach ($deviceIds as $deviceId) {
    //                         $messaging->send($message->withChangedTarget('token', $deviceId));
    //                     }
                        
    //                 } catch (NotFound $e) {
    //                     //echo $e;
    //                 } catch (InvalidArgument $e) {
    //                     //echo $e;
    //                 }
    //             }
                
                

                
    //         }
    //     }
      
    // }


    // public function userNotification($data)
    // {
    //     $firebase = (new Factory)
    //     ->withServiceAccount(config_path().'/firebase_credentials.json');

    //     $messaging = $firebase->createMessaging();
    //     if(!empty($data))
    //     {
    //         $user_id = $data['user_id'];
    //         $user = User::findOrFail($user_id);
    //         $title = $data['title'];
    //         $name = $data['name'];

    //         $body = $title;
    //         $type = $data['type'] == 'like' ? ' like your news!': ' add comment on your news!';
    //         $notifyTitle = $name.''.$type;

    //         if(!empty($user) && isset($user->fcm_token) && $user->fcm_token !='')
    //         {
    //             $deviceToken = $user->fcm_token;
    //             $message = CloudMessage::withTarget('token', $deviceToken)
    //             ->withNotification([
    //                 'title' => $notifyTitle,
    //                 'body' => $body
    //             ]);

    //             try {
    //                 $messaging->send($message);
                    
    //             } catch (NotFound $e) {
    //                 //echo $e;
    //             } catch (InvalidArgument $e) {
    //                 //echo $e;
    //             }
    //         }
    //     }
      
    // }

    public function isValidFireBaseMessagingToken($token)
    {
        try {
            $messaging = app()->make(Messaging::class);
            $appInstance = $messaging->getAppInstance($token);
            return $appInstance->rawData();
        } catch (\Throwable $e) {
            //echo $e->getMessage();
            
            return false;
        }
    }

    
}

