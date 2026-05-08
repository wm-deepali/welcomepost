<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Kreait\Firebase\Factory;

class NotificationController extends Controller
{
    public function sendNotification(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string|max:255',
            'users' => 'nullable|array',
            'image' => 'nullable|image|max:1024', // 1MB max
        ]);
    
        $title = $request->input('title');
        $body = $request->input('body');
        $imageUrl = null;
    
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('notifications', 'public');
            $imageUrl = asset('storage/app/public/' . $imagePath);
        }
    
        $credentialsPath = config('services.firebase.credentials');
        $factory = (new Factory)->withServiceAccount(base_path($credentialsPath));
        $messaging = $factory->createMessaging();
    
          
        $notification = [
            'title' => $title,
            'body' => $body,
        ];

        if ($imageUrl) {
            $notification['image'] = $imageUrl;
        }
    
        if ($request->has('send_to_all')) {
            // Send to topic 'all'
            $message = CloudMessage::withTarget('topic', 'all')
                ->withNotification(Notification::fromArray($notification));
        } else {
            // Send to specific users
            if(!isset($request->users)){
                return redirect()->back()->with([
                    'error' => "dd",
                    'success' => false,
                    'message' => 'Please specify the user!!'
                ]);
            }
            $tokens = Customer::whereIn('id', $request->input('users'))
                ->pluck('fcm_token')
                ->filter()
                ->all();
    
            if (empty($tokens)) {
                return response()->json(['success' => false, 'message' => 'No valid FCM tokens found for selected users'], 400);
            }
    
            $message = CloudMessage::new()
                ->withNotification(Notification::fromArray($notification));
    
            try {
                $report = $messaging->sendMulticast($message, $tokens);
                $successful = $report->successes()->count();
                $failed = $report->failures()->count();
        
                return redirect()->back()->with([
                    'success' => true,
                    'message' => 'Notification sent successfully',
                    'details' => [
                        'successful' => $successful,
                        'failed' => $failed
                    ]
                ]);
            } catch (FirebaseException $e) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
        }
    
        try {
            $messaging->send($message);
    
            return redirect()->back()->with([
                'success' => true,
                'message' => 'Notification sent successfully to the topic.',
            ]);
        } catch (FirebaseException $e) {
            // Log or display the error message for debugging
            error_log('FirebaseException: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            // Handle any other exceptions
            error_log('Exception: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

}
