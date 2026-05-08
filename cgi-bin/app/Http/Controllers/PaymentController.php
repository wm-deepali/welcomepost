<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use LoveyCom\CashFree\PaymentGateway\Order;
use Session;
use App\Models\WalletAmout;
use App\Models\Subscription;
use App\Models\Customer;
use App\Models\SubscriptionOrder;

class PaymentController extends Controller
{
   public function orderProcess(Request $request)
    {
        try {
            $order = new Order();
            $od["orderId"] = "ORDER-" . rand(1000, 9999);
            $od["orderAmount"] = $request->input('price');
            $od["orderNote"] = $request->input('description');
            $od["customerPhone"] = $request->input('phone');
            $od["customerName"] = $request->input('name');
            $od["customerEmail"] = $request->input('email');
            $od["orderCurrency"] = "INR";
            $od["businessName"] = "WelcomePost";
            $od["returnUrl"] = route('order.status');
            $od["notifyUrl"] = route('purchase-subscription');
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
			$userprofile->transaction_id			= $od["orderId"];
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
            $order->create($od);
            
            
            // Get the payment link of this order for your customer
            $link = $order->getLink($od['orderId']);
            
            // Return the payment link in JSON format
            return response()->json(['paymentLink' => $link->paymentLink]);
        } catch (\Exception $e) {
            // Log the error
            \Log::error($e);
            
            // Handle the error gracefully
            return response()->json(['error' => 'An error occurred while processing your order. Please try again later. '.$e], 500);
        }
    }
    
    public function orderStatus(Request $request){
        $orderId = $request->input('orderId');
        $order = SubscriptionOrder::where('transaction_id', $orderId)->first();
        $user = Customer::findOrFail($order->user_id);
        if ($order) {
            if($request->txStatus=='USER_DROPPED'){
                $request->session()->put('id',$order->user_id);
                $order->payment_status = 'Cancelled';
                $order->save();
                return redirect()->route('purchase-subscription')->with('error','User cancelled the payment.');
            }
            if($request->txStatus=='SUCCESS'){
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
                return view('website.temprory-redireaction')->with(['id' => $order->subscription_id,'wallet_remaining' => 10,'cashfree' => $cashfree,'payment_id'=>$orderId,'total_subscription'=> $request->orderAmount,'total_wout_gst'=>$totalWithoutGst??null]);
            }
            if($request->txStatus=='FAILED'){
                $request->session()->put('id',$order->user_id);
                $order->payment_status = 'Failed';
                $order->save();
                return redirect()->route('purchase-subscription')->with('error','Transaction failed.');
            }
        }
        return redirect()->route('login')->with('error', 'Invalid order');
        
    }
}
