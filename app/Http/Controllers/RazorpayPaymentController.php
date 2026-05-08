<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Categories;
use App\Models\Subcategories;
use App\Models\Subscription;
use App\Models\SubscriptionOrder;
use App\Models\Blog;
use App\Models\Customer;
use App\Models\Contact;
use App\Models\Faq;
use App\Models\Job;
use App\Models\Brand;
use App\Models\Furnishing;
use App\Models\Construction;
use App\Models\About;
use App\Models\CallBack;
use App\Models\Subject;
use App\Models\RaiseTicket;
use App\Models\Pages;
use App\Models\Countries;
use App\Models\States;
use App\Models\City;
use App\Models\Zip;
use App\Models\Location;
use App\Models\Adsenquiry;
use App\Models\Adposting;
use App\Models\Jobforms;
use App\Models\Mobileform;
use App\Models\Vehicleform;
use App\Models\Vehicletypes;
use App\Models\Propertyform;
use App\Models\Fueltype;
use App\Models\Transmission;
use App\Models\Residence;
use App\Models\Commonform;
use App\Models\Enquiry;
use App\Models\AdPostingImage;
use App\Models\RazorpaySetting;
use Session;
use Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Post;
use App\Exports\PostExport;
use App\Exports\SubscriptionExport;
use DB;
use Mail;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use PDF;
use App\Models\Event;
use App\Models\StoreEvent;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Mail\ActivationAccount;
use Razorpay\Api\Api;
use Exception;

class RazorpayPaymentController extends Controller
{
    public function store(Request $request)
    {
        $input              = $request->all();
        $subscription_id    = $request->id;
        $setting            = RazorpaySetting::first();
        $api                = new Api($setting->key_id, $setting->secret_id);
        $payment            = $api->payment->fetch($input['razorpay_payment_id']);
        
        $user_id 	        = session('id');
        //$rand 		        = mt_rand(1500, 5000);
        $result 		    = DB::table('subscriptions')->where('id',$subscription_id)->get();
		$no_of_ads 		    = $result[0]->no_of_ads;
		$ads_validity 	    = $result[0]->ads_validity;
		$category_id 	    = $result[0]->category_id;
		$package_validity 	= $result[0]->package_validity;
		$no 	            = explode(" ",$package_validity);	 
		$dates 	            = date("d-m-Y");	
		$date   	        = date_create($dates);
		date_add($date,date_interval_create_from_date_string($no[0]."days"));
		$subscription_expiry = date_format($date,"d-m-Y");
		
		$paymentmethod  = 'online';
		$payment_status = 'Completed';
		$transaction_id = $input['razorpay_payment_id'];
  
        if(count($input)  && !empty($input['razorpay_payment_id'])) {
            try {
                $response           = $api->payment->fetch($input['razorpay_payment_id'])->capture(array('amount'=>$payment['amount']));
                $subscriber_exists  = DB::table('subscription_orders')->where('user_id',$user_id)->where('category_id',$category_id)->exists();
                
		        if($subscriber_exists)
		        {
			        DB::table('subscription_orders')->where('user_id',$user_id)->where('category_id',$category_id)->update(array('user_id'=>$user_id,'subscription_id'=>$request->id,'transaction_id'=>$transaction_id,'payment_method'=>$paymentmethod,'payment_status'=>$payment_status,'used_ads'=>'0','remaining_ads'=>$no_of_ads,'subscription_validity'=>$ads_validity,'category_id'=>$category_id,'delete_status'=>'0','status'=>'0'));

		        }else
		        {
			        $userprofile					= new SubscriptionOrder;
    			    $userprofile->user_id 					= $user_id;
        			$userprofile->subscription_id 			= $request->id;
        			$userprofile->transaction_id			= $transaction_id;
        			$userprofile->payment_method			= $paymentmethod;
        			$userprofile->payment_status			= $payment_status;
        			$userprofile->used_ads					= '0';
        			$userprofile->remaining_ads				= $no_of_ads;
        			//$userprofile->subscription_expiry 	=$subscription_expiry;
        			$userprofile->subscription_validity		= $package_validity;
        			$userprofile->category_id   			= $category_id;
        			$userprofile->delete_status				= '0';
        			$userprofile->status 					= '0';
        			$userprofile->save();
		        }
		        

		        DB::table('subscription_history')->insert(
			        array(
        				'user_id'				=> $user_id,
        				'subscription_id'		=> $request->id,
        				'transaction_id'		=> $transaction_id,
        				'payment_method'		=> $paymentmethod,
        				'payment_status'		=> $payment_status,
        				'used_ads'				=> '0',
        				'remaining_ads'			=> $no_of_ads,
        				//'subscription_expiry'	=> $subscription_expiry,
        				'subscription_validity'	=> $package_validity,
        				'category_id'			=> $category_id,
        				'delete_status'			=> '0',
        				'status'				=> '0'
        			));
                
            } catch (Exception $e) {
                return  $e->getMessage();
                Session::put('error',$e->getMessage());
                return redirect()->back();
            }
        }
          
        Session::put('success', 'Payment successful');
        return redirect()->back();
    }
}