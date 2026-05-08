<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use ImageOptimizer;
use App\Mail\BlockMail;
use App\Mail\AccountTermination;
use App\Models\OTP;
use App\Models\LoginAttempt;
use App\Mail\TicketMail;
use App\Traits\FCMNotifications;
use App\Mail\OtpEmailNotification;
use Illuminate\Support\Facades\Auth;
use App\Models\Categories;
use App\Models\AdView;
use App\Models\Faqcategory;
use App\Models\Subcategories;
use App\Models\Subscription;
use App\Models\LevelTransaction;
use App\Models\CommissionLevel;
use App\Models\SubscriptionOrder;
use App\Models\Blog;
use App\Models\InfoCard;
use App\Models\Customer;
use App\Models\CustomerVerify;
use App\Models\RazorpaySetting;
use App\Models\CustomNotificationHistory;
use App\Models\DefaultNotificationHistory;
use App\Models\Contact;
use App\Models\Faq;
use App\Models\Job;
use App\Models\Brand;
use App\Models\Furnishing;
use App\Models\Construction;
use App\Models\Facing;
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
use DateTime;
use App\Models\Transmission;
use App\Models\Residence;
use App\Models\Commonform;
use App\Models\Enquiry;
use App\Models\AdPostingImage;
use App\Models\managecommission;
use App\Models\WalletAmout;
use App\Models\Customer_child;
use Session;
use Validator;
use DB;
use Mail;
use App\Http\Controllers\Controller;
use PDF;
use App\Models\PrimeUser;
use App\Models\Adminsettings;
use App\Models\SubscriptionHistory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Mail\ActivationAccount;
use App\Mail\EmailVerificationEmail;
use App\Models\Banner;
use App\Models\CustomerCommission;
use Razorpay\Api\Api;
use Exception;

use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use App\Models\CustomerTemp;
use App\Models\DefaultNotification;

class WebsiteController extends Controller
{
	use FCMNotifications;
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	const MAX_ATTEMPTS = 3;

	public function testuser()
	{

		$currentdate = date("d-m-Y");
		$currentDate = date("Y-m-d", strtotime("now"));
		$currentdate = date("d-m-Y");
		$currentDate = date("Y-m-d", strtotime("now"));

		try {
			DB::beginTransaction();
			\Log::info('Your function test user was called.');

			$adspostings = Adposting::select('subscription_id', DB::raw('SUM(ad_view_count) as total_views_sum'))->where('active_status', '1')->groupBy('subscription_id')->get();
			foreach ($adspostings as $adsposting) {
				if ($adsposting->subscriptionhistory->type == 'Prime') {
					if ($adsposting->total_views_sum >= ($adsposting->subscriptionhistory->minimum_views ?? 0)) {
						SubscriptionHistory::where('id', $adsposting->subscription_id)->update(['type' => 'Premium']);
					}
				}
			}
			SubscriptionHistory::whereDate('subscription_expiry', '<', date('Y-m-d'))->update(['type' => 'Normal']);
			Customer::whereDate('reserve_expiry_at', '<', date('Y-m-d'))->update(['parent_id' => NULL, 'reserve_expiry_at' => NULL]);
			Customer_child::whereDate('reserve_expiry_at', '<', date('Y-m-d'))->update(['status' => 'Removed', 'removal_date' => date('Y-m-d')]);


			$adminsetting = \App\Models\Adminsettings::first();
			if ($adminsetting->auto_join == "1") {
				$customersp = SubscriptionHistory::where('join_complete', 'no')->orderByRaw("CASE WHEN type = 'Premium' THEN 1 WHEN type = 'Prime' THEN 2 ELSE 3 END")->where('auto_join', 1)->orderBy('created_at', 'ASC')->get();
				\Log::info(' Loop started..');
				foreach ($customersp as $customers) {
					if ($customers->customers->membership_expiry_at >= date('Y-m-d')) {
						\Log::info($customers->customers->name . 'Your auto join test user was called.');
						$totaljoined = $customers->total_joined;
						if ($customers->auto_join_member != $customers->total_joined) {
							$customersas = Customer::where('id', '!=', $customers->user_id)->where('id', "!=", $customers->customers->parent_id)->whereDate('membership_expiry_at', '>=', date('Y-m-d'))->whereNull('parent_id')->select('id')->orderBy('created_at', 'ASC')->limit($customers->auto_join_member - $customers->total_joined)->get();
							foreach ($customersas as $customersas1) {
								$reserve_expiry_at = (new DateTime())->modify('+' . $adminsetting->reserve_expiry_timeline . ' days')->format('Y-m-d');
								Customer_child::create([
									'user_id' => $customers->user_id,
									'child_id' => $customersas1->id,
									'subscription_id' => $customers->id,
									'joining_date' => date('Y-m-d'),
									'reserve_expiry_at' => $reserve_expiry_at,
									'status' => 'Active',
								]);

								$customersas1->update(['parent_id' => $customers->user_id, 'reserve_expiry_at' => $reserve_expiry_at]);
								$totaljoined = $totaljoined + 1;
							}
						}
						$joincomplete = $totaljoined == $customers->auto_join_member ? "yes" : "no";
						$customers->update(['join_complete' => $joincomplete, 'total_joined' => $totaljoined, 'type' => $joincomplete == "yes" ? "Prime" : $customers->type]);
					}

				}
			}

			//   $subscriptionhistorys =   SubscriptionHistory::where('comission_paid','no')->get();
			//   foreach($subscriptionhistorys as $subscriptionhistory){
			//       if($subscriptionhistory->customers->parent_id){
			//           $subscriptionhistory->update(['comission_paid_parent_id'=>$subscriptionhistory->customers->parent_id,'comission_paid'=>'yes']);
			//       }
			//   }



			DB::commit();
		} catch (\Exception $ex) {
			DB::rollback();
			echo $ex->getMessage() . '-' . $ex->getLine();
		}
		//  return true;
		//  print_r($customersp);
		//  die();
	}

	public function sendNoti()
	{
		$subscriptions = SubscriptionHistory::whereDate('subscription_expiry', '<=', date('Y-m-d'))->get();
		foreach ($subscriptions as $subscription) {
			$customerX = Customer::where('id', $subscription->user_id)->first();

			if ($customerX->fcm_token) {
				$title = 'Subscription Expired!';
				$body = $subscription->subscription_number . ' Package Expired';
				$image = null;
				$response = $this->sendNotification($title, $body, $customerX->fcm_token, $image);
			}
		}
	}

	public function removeWelcomeAmount(Request $request)
	{
		$request->session()->forget('welcomeAmount');

		return response()->json(['message' => 'Session value removed successfully']);
	}
	public function index(Request $request)
	{

		if (session()->has('id')) {
			$userExist = Customer::where('id', session()->get('id'))
				->whereNull('password')
				->exists();
			if ($userExist) {
				return redirect()->route('first.details');
			}
		}

		$data['PremiumCategories'] = Categories::where('delete_status', '0')->where('premium', '1')->orderby('id', 'desc')->get();
		$data['TopCategories'] = Categories::where('delete_status', '0')->where('top', '1')->orderby('id', 'desc')->get();
		$data['TrendingCategories'] = Categories::where('delete_status', '0')->where('trending', '1')->orderby('id', 'desc')->get();
		$data['allcategories'] = Categories::where('delete_status', '0')->orderby('name', 'ASC')->get();
		$data['Pages'] = Pages::where('delete_status', '0')->get();
		$data['city'] = City::where('delete_status', '0')->orderby('name', 'asc')->get();
		$userIp = $request->ip();
		$data['locationinfo'] = \Location::get($userIp);

		if ($data['locationinfo']) {
			$data['cityAd'] = City::where('name', session('city_name', $data['locationinfo']->cityName))->where('delete_status', '0')->first();
		} else {
			$data['cityAd'] = '';
		}
		$adType = 'Paid';

		$data['jobads'] = Adposting::where('delete_status', '0')->where('status', '1')->orderByRaw("IF(ad_type = '{$adType}', ad_type, id) DESC")->whereDate('ad_expiry', '>', date('Y-m-d'))->get();
		$data['subscription'] = Subscription::where('delete_status', '0')->where('status', '0')->orderby('offered_price', 'DESC')->get();
		$data['homecategories'] = Categories::where('delete_status', '0')->where('top', 1)->limit(8)->get();
		$data['page'] = 'Home';
		$data["banners"] = Banner::all();
		$data['infocards'] = InfoCard::where('status', 'Active')->get();
		$data['adminSetting'] = Adminsettings::first();
		$data['block_count'] = DB::table('block_count')->where('user_id', session('id'))->value('count');
		return view('website.home', $data);
	}

	public function setCitySession(Request $request)
	{
		$request->session()->put('city_id', $request->city_id);
		$request->session()->put('city_name', $request->city_name);

		return response()->json(['success' => true]);
	}

	public function addRequiredDetails(Request $request)
	{

		$user_id = session()->get('id_tempuser');
		$data['user'] = CustomerTemp::findOrFail($user_id);
		// echo"<pre>";print_r($data['user']);die();
		$data['PremiumCategories'] = Categories::where('delete_status', '0')->where('premium', '1')->orderby('id', 'desc')->get();
		$data['TopCategories'] = Categories::where('delete_status', '0')->where('top', '1')->orderby('id', 'desc')->get();
		$data['TrendingCategories'] = Categories::where('delete_status', '0')->where('trending', '1')->orderby('id', 'desc')->get();
		$data['allcategories'] = Categories::where('delete_status', '0')->orderby('name', 'ASC')->get();
		$data['Pages'] = Pages::where('delete_status', '0')->get();
		$data['city'] = City::where('delete_status', '0')->orderby('name', 'asc')->get();
		$data['states'] = States::where('delete_status', '0')->orderby('name', 'asc')->get();
		$userIp = $request->ip();
		$data['locationinfo'] = \Location::get($userIp);
		$data['page'] = 'Add Details';

		return view('website.add-details', $data);
	}

	public function storeRequiredDetails(Request $request)
	{
		// dd($request->all());
		$request->validate([
			'referralto' => 'nullable|string|max:255',
			'password' => 'required|string|min:8',
			//'mobile' => 'required|min:10|max:10',
			'country' => 'required',
			'state' => 'required',
			'city' => 'required',
			'pin' => 'required|max:10',
		]);

		// Retrieve the customer ID from the session
		$user_id = session()->get('id_tempuser');

		$customer_temp = CustomerTemp::find($user_id);
		// echo"<pre>";print_r($customer_temp);die();
		if (!$user_id) {
			return redirect()->back()->with('error', 'Customer not found in session.');
		}

		if (!$request->isValid) {
			\Session::put('error', 'First verify your phone number..');
			return redirect()->back();
		}

		// Retrieve the customer from the database
		$adminsetting = Adminsettings::first();

		$emailex = Customer::where('email', $customer_temp->email)->first();
		if (!empty($emailex)) {
			\Session::put('error', 'Email already exist...');
			return redirect()->back();
		}


		$customer = Customer::create([
			'name' => $customer_temp->name,
			'email' => $customer_temp->email,
			'google_id' => $customer_temp->google_id,
			'referral_code' => $customer_temp->referral_code,
			'image' => $customer_temp->image,
			'is_email_verified' => '1',
			'membership_expiry_at' => $customer_temp->membership_expiry_at,
			'wallet_bonus' => $customer_temp->wallet_bonus,
			'datetime' => $customer_temp->datetime,
			'delete_status' => '0',
			'status' => '0',
			'no_of_ads' => '0',
			'is_email_verified' => '1',
			'member_id' => $customer_temp->member_id,
			'user_type' => 'Free'
		]);
		if ($adminsetting->welcome_amount > 0) {
			$walletamout = new WalletAmout();
			$walletamout->amount = $customer->wallet_bonus;
			$walletamout->userid = $customer->id;
			$walletamout->status = "1";
			$walletamout->datetime = date("d/m/y/ h:i:s A");
			$walletamout->description = "₹" . $customer->wallet_bonus . " credited to your wallet for welcome bonus";
			$walletamout->save();
			$welcomeAmount = $adminsetting->welcome_amount;
			//$request->session()->put('id',$newUser->id);
			//return redirect()->route('first.details');

			$event = DefaultNotification::where('event', 'wallet_credit')->first();
			if (!empty($event)) {
				$title = $event->title;
				$content = $event->content;
				$body = str_replace("#amount", $customer->wallet_bonus, $content);
				$notifyArray = array(
					'user_id' => $customer->id,
					'event_name' => $event->event,
					'title' => $title,
					'body' => $body,
				);

				$this->singleUserNotification($notifyArray);
			}

		}
		$customer = Customer::find($customer->id);
		if (!$customer) {
			return redirect()->back()->with('error', 'Customer not found.');
		}
		$referCustomerExist = Customer::with('subscriptionhistory')->where('referral_code', $request->referralto)->exists();
		if ($referCustomerExist) {
			$user_ref_id = Customer::with('subscriptionhistory')->where('referral_code', $request->referralto)->first();
		}
		if (isset($request->referralto)) {
			$reserve_expiry_at = (new DateTime())->modify('+' . $adminsetting->reserve_expiry_timeline . ' days')->format('Y-m-d');
		} else {
			$reserve_expiry_at = null;
		}
		if ($referCustomerExist && $adminsetting->is_active_ad_referral) {
			$subscription = SubscriptionHistory::where('user_id', $user_ref_id->id)->where('type', '!=', 'Normal')->whereDate('subscription_expiry', '>=', Carbon::now())->exists();
			//$userAdsExist = Adposting::where('user_id',$user_ref_id->id)->where('active_status',1)->where('status',1)->where('delete_status',0)->exists();
		} else {
			$subscription = true;
		}
		// Update the customer instance
		$customer->password = bcrypt($request->password);
		if ($referCustomerExist && $subscription) {
			$customer->parent_id = $user_ref_id->id ?? null;
			$customer->reserve_expiry_at = $reserve_expiry_at;
			$customer->referralto = $request->referralto;
		}
		$customer->mobile = $request->mobile;
		$customer->country = $request->country;
		$customer->state = $request->state;
		$customer->city = $request->city;
		$customer->pin = $request->pin;
		$customer->save();

		/****************New Added Admin email *****/
		$userStateName = States::where('id', $request->state)->first();

		$userCityName = City::where('id', $request->city)->first();
		$customerEmailDetail = array(
			'name' => $customer->name,
			'password' => $request->password,
			'email' => $customer->email,
			'mobile' => $customer->mobile,
			'member_id' => $customer->member_id,
			'pin' => $customer->pin,
			'state' => $userStateName->name ?? "",
			'city' => $userCityName->name ?? "",
			'country' => 'India',
		);
		$messagead = '';
		Mail::send('email.new-user-register', $customerEmailDetail, function ($messagead) use ($customerEmailDetail) {
			$messagead->to('choudharyfaizasif@gmail.com', 'Welcome Post')->subject('New User Registered on Welcome Post');
			$messagead->from($customerEmailDetail['email'], $customerEmailDetail['name']);
		});

		if ($referCustomerExist && $subscription) {
			Customer_child::create([
				'user_id' => $user_ref_id->id,
				'child_id' => $customer->id,
				'subscription_id' => $user_ref_id->subscriptionhistory()->whereDate('subscription_expiry', '>=', Carbon::now())->orderBy('created_at', 'desc')->first()->id ?? null,
				'joining_date' => date('Y-m-d'),
				'reserve_expiry_at' => $reserve_expiry_at,
				'status' => 'Active',
			]);

			$event = DefaultNotification::where('event', 'new_seeding')->first();
			if (!empty($event)) {
				$title = $event->title;
				$content = $event->content;
				$body = str_replace("#member_id", $customer->member_id, $content);
				$notifyArray = array(
					'user_id' => $user_ref_id->id,
					'event_name' => $event->event,
					'title' => $title,
					'body' => $body,
				);

				$this->singleUserNotification($notifyArray);
			}
		}
		$request->session()->put('id', $customer->id);
		// Redirect with success message
		$request->session()->forget(['referralCode', 'refUserName']);
		return redirect()->route('/')->with('welcomeAmount', $adminsetting->welcome_amount);
	}

	public function user_side(Request $request)
	{
		$data['PremiumCategories'] = Categories::where('delete_status', '0')->where('premium', '1')->orderby('id', 'desc')->get();
		$data['TopCategories'] = Categories::where('delete_status', '0')->where('top', '1')->orderby('id', 'desc')->get();
		$data['TrendingCategories'] = Categories::where('delete_status', '0')->where('trending', '1')->orderby('id', 'desc')->get();
		$data['allcategories'] = Categories::where('delete_status', '0')->orderby('name', 'ASC')->get();
		$data['Pages'] = Pages::where('delete_status', '0')->get();
		$data['city'] = City::where('delete_status', '0')->orderby('name', 'asc')->get();
		$userIp = $request->ip();
		$data['locationinfo'] = \Location::get($userIp);
		$adType = 'Paid';
		$data['jobads'] = Adposting::where('delete_status', '0')->where('status', '1')->orderByRaw("IF(ad_type = '{$adType}', ad_type, id) DESC")->get();
		$data['subscription'] = Subscription::where('delete_status', '0')->where('status', '0')->orderby('offered_price', 'DESC')->get();
		$data['homecategories'] = Categories::where('delete_status', '0')->limit(8)->get();
		$data['page'] = 'User Side';
		return view('website.user-side', $data);
	}

	public function user_wallets(Request $request)
	{
		$data['PremiumCategories'] = Categories::where('delete_status', '0')->where('premium', '1')->orderby('id', 'desc')->get();
		$data['TopCategories'] = Categories::where('delete_status', '0')->where('top', '1')->orderby('id', 'desc')->get();
		$data['TrendingCategories'] = Categories::where('delete_status', '0')->where('trending', '1')->orderby('id', 'desc')->get();
		$data['allcategories'] = Categories::where('delete_status', '0')->orderby('name', 'ASC')->get();
		$data['Pages'] = Pages::where('delete_status', '0')->get();
		$data['city'] = City::where('delete_status', '0')->orderby('name', 'asc')->get();
		$userIp = $request->ip();
		$data['locationinfo'] = \Location::get($userIp);
		$data['jobads'] = Adposting::where('delete_status', '0')->where('status', '1')->orderby('id', 'DESC')->get();
		$data['subscription'] = Subscription::where('delete_status', '0')->where('status', '0')->orderby('offered_price', 'DESC')->get();
		$data['homecategories'] = Categories::where('delete_status', '0')->limit(8)->get();
		$data['customers'] = Customer::where('id', session('id'))->first();
		$data['transaction_history'] = WalletAmout::where('userid', session('id'))->orderByDesc('created_at')->get();
		// 		$data['currentMonthTotal'] = WalletAmout::where('userid',session('id'))->where('status', 1)
// 						->whereYear('created_at', now()->year)
// 						->whereMonth('created_at', now()->month)
// 						->sum('amount');
// 		$data['lifeTimeEarning'] = WalletAmout::where('userid',session('id'))->where('status', 1)
// 								->sum('amount');
		$lifeTimeEarning = CustomerCommission::where('parent_id', session('id'))->where('status', 'approved')->get();
		if ($lifeTimeEarning->count() > 0) {
			$data['lifeTimeEarning'] = $lifeTimeEarning->sum('total_earned');
		}
		$pendingRelease = CustomerCommission::where('parent_id', session('id'))
			->whereYear('created_at', now()->year)
			->whereMonth('created_at', now()->subMonth()->month)
			->where('status', 'pending')
			->get();

		if ($pendingRelease->count() > 0) {
			$data['pendingReleaseEarning'] = $pendingRelease->sum('total_earned');
		}

		$releasedEarn = CustomerCommission::where('parent_id', session('id'))->whereYear('created_at', now()->year)
			->whereMonth('created_at', now()->month)->where('status', 'approved')->get();
		if ($releasedEarn->count() > 0) {
			$data['releasedEarning'] = $releasedEarn->sum('total_earned');
		}
		$currentMonthPending = CustomerCommission::where('parent_id', session('id'))
			->whereYear('created_at', now()->year)
			->whereMonth('created_at', now()->month)
			->where('status', 'pending')
			->get();
		if ($currentMonthPending->count() > 0) {
			$data['currentMonthPending'] = $currentMonthPending->sum('total_earned');
		}
		$pendingReleaseEarning = CustomerCommission::where('parent_id', session('id'))
			->whereYear('created_at', now()->year)
			->where('status', 'pending')
			->get();
		if ($pendingReleaseEarning->count() > 0) {
			$data['pendingReleaseEarning'] = $pendingReleaseEarning->sum('total_earned');
		}
		$data['page'] = 'Wallet';
		return view('website.user-wallets', $data);
	}

	public function transaction_history(Request $request)
	{
		$data['PremiumCategories'] = Categories::where('delete_status', '0')->where('premium', '1')->orderby('id', 'desc')->get();
		$data['TopCategories'] = Categories::where('delete_status', '0')->where('top', '1')->orderby('id', 'desc')->get();
		$data['TrendingCategories'] = Categories::where('delete_status', '0')->where('trending', '1')->orderby('id', 'desc')->get();
		$data['allcategories'] = Categories::where('delete_status', '0')->orderby('name', 'ASC')->get();
		$data['Pages'] = Pages::where('delete_status', '0')->get();
		$data['city'] = City::where('delete_status', '0')->orderby('name', 'asc')->get();
		$userIp = $request->ip();
		$data['locationinfo'] = \Location::get($userIp);
		$adType = 'Paid';
		$data['jobads'] = Adposting::where('delete_status', '0')->where('status', '1')->orderByRaw("IF(ad_type = '{$adType}', ad_type, id) DESC")->get();
		$data['subscription'] = Subscription::where('delete_status', '0')->where('status', '0')->orderby('offered_price', 'DESC')->get();
		$data['homecategories'] = Categories::where('delete_status', '0')->limit(8)->get();
		$data['page'] = 'Transaction History';
		return view('website.transaction-history', $data);
	}

	public function my_earning(Request $request)
	{
		$data['PremiumCategories'] = Categories::where('delete_status', '0')->where('premium', '1')->orderby('id', 'desc')->get();
		$data['TopCategories'] = Categories::where('delete_status', '0')->where('top', '1')->orderby('id', 'desc')->get();
		$data['TrendingCategories'] = Categories::where('delete_status', '0')->where('trending', '1')->orderby('id', 'desc')->get();
		$data['allcategories'] = Categories::where('delete_status', '0')->orderby('name', 'ASC')->get();
		$data['Pages'] = Pages::where('delete_status', '0')->get();
		$data['city'] = City::where('delete_status', '0')->orderby('name', 'asc')->get();
		$userIp = $request->ip();
		$data['locationinfo'] = \Location::get($userIp);
		$data['jobads'] = Adposting::where('delete_status', '0')->where('status', '1')->orderby('id', 'DESC')->get();
		$data['subscription'] = Subscription::where('delete_status', '0')->where('status', '0')->orderby('offered_price', 'DESC')->get();
		$data['homecategories'] = Categories::where('delete_status', '0')->limit(8)->get();
		$data['page'] = 'My Earning';
		$data['my_earnings'] = CustomerCommission::where('parent_id', session('id'))
			->where('status', 'pending')
			->get();
		return view('website.my-earning', $data);
	}

	public function searchCategories(Request $request)
	{
		$query = $request->input('q');
		$categories = Categories::where('name', 'LIKE', '%' . $query . '%')->get();
		return response()->json($categories);
	}

	public function my_referrals(Request $request)
	{
		$data['PremiumCategories'] = Categories::where('delete_status', '0')->where('premium', '1')->orderby('id', 'desc')->get();
		$data['TopCategories'] = Categories::where('delete_status', '0')->where('top', '1')->orderby('id', 'desc')->get();
		$data['TrendingCategories'] = Categories::where('delete_status', '0')->where('trending', '1')->orderby('id', 'desc')->get();
		$data['allcategories'] = Categories::where('delete_status', '0')->orderby('name', 'ASC')->get();
		$data['Pages'] = Pages::where('delete_status', '0')->get();
		$data['city'] = City::where('delete_status', '0')->orderby('name', 'asc')->get();
		$userIp = $request->ip();
		$data['locationinfo'] = \Location::get($userIp);
		$adType = 'Paid';
		$data['jobads'] = Adposting::where('delete_status', '0')->where('status', '1')->orderByRaw("IF(ad_type = '{$adType}', ad_type, id) DESC")->get();
		$data['subscription'] = Subscription::where('delete_status', '0')->where('status', '0')->orderby('offered_price', 'DESC')->get();
		$data['homecategories'] = Categories::where('delete_status', '0')->limit(8)->get();
		$data['page'] = 'My Referrals';
		$data['my_referral_id'] = Customer::findOrFail(session()->get('id'))->referral_code;
		$data['referals'] = Customer::where('parent_id', session()->get('id'))->whereNotNull('referralto')->get();
		$data['adminsetting'] = Adminsettings::first();
		return view('website.my-referral', $data);
	}

	public function myautojoining(Request $request)
	{
		$data['PremiumCategories'] = Categories::where('delete_status', '0')->where('premium', '1')->orderby('id', 'desc')->get();
		$data['TopCategories'] = Categories::where('delete_status', '0')->where('top', '1')->orderby('id', 'desc')->get();
		$data['TrendingCategories'] = Categories::where('delete_status', '0')->where('trending', '1')->orderby('id', 'desc')->get();
		$data['allcategories'] = Categories::where('delete_status', '0')->orderby('name', 'ASC')->get();
		$data['Pages'] = Pages::where('delete_status', '0')->get();
		$data['city'] = City::where('delete_status', '0')->orderby('name', 'asc')->get();
		$userIp = $request->ip();
		$data['locationinfo'] = \Location::get($userIp);
		$adType = 'Paid';
		$data['jobads'] = Adposting::where('delete_status', '0')->where('status', '1')->orderByRaw("IF(ad_type = '{$adType}', ad_type, id) DESC")->get();
		$data['subscription'] = Subscription::where('delete_status', '0')->where('status', '0')->orderby('offered_price', 'DESC')->get();
		$data['homecategories'] = Categories::where('delete_status', '0')->limit(8)->get();
		$data['page'] = 'Auto Joining';
		$data['autojoins'] = SubscriptionHistory::where('user_id', session()->get('id'))->where('auto_join_member', '!=', '0')->get();
		return view('website.my-autojoinmember', $data);
	}

	public function payouts(Request $request)
	{
		$data['PremiumCategories'] = Categories::where('delete_status', '0')->where('premium', '1')->orderby('id', 'desc')->get();
		$data['TopCategories'] = Categories::where('delete_status', '0')->where('top', '1')->orderby('id', 'desc')->get();
		$data['TrendingCategories'] = Categories::where('delete_status', '0')->where('trending', '1')->orderby('id', 'desc')->get();
		$data['allcategories'] = Categories::where('delete_status', '0')->orderby('name', 'ASC')->get();
		$data['Pages'] = Pages::where('delete_status', '0')->get();
		$data['city'] = City::where('delete_status', '0')->orderby('name', 'asc')->get();
		$userIp = $request->ip();
		$data['locationinfo'] = \Location::get($userIp);
		$adType = 'Paid';
		$data['jobads'] = Adposting::where('delete_status', '0')->where('status', '1')->orderByRaw("IF(ad_type = '{$adType}', ad_type, id) DESC")->get();
		$data['subscription'] = Subscription::where('delete_status', '0')->where('status', '0')->orderby('offered_price', 'DESC')->get();
		$data['homecategories'] = Categories::where('delete_status', '0')->limit(8)->get();
		$data['page'] = 'Payouts';

		// Query the CustomerCommission model
		$data['commission'] = CustomerCommission::where('parent_id', session('id'))
			->whereYear('created_at', now()->year)
			->whereMonth('created_at', now()->month)
			->select(
				'id',
				'status',
				'created_at',
				'reason',
				'image',
				'payment_method',
				'level_transaction_id',
				DB::raw('IFNULL(parent_id, user_id) AS parent_id'), // Set parent_id to 0 if it's null
				DB::raw('SUM(total_commission) as total_commission'),
				DB::raw('SUM(tds) as total_tds'),
				DB::raw('SUM(admin_charges) as total_admin_charges'),
				DB::raw('SUM(other_charges) as total_other_charges'),
				DB::raw('SUM(total_earned) as total_earned')
			)
			->groupBy('parent_id', 'user_id', 'status', 'created_at', 'id', 'reason', 'image', 'payment_method')
			->get();
		return view('website.payouts', $data);
	}

	public function Myteam(Request $request)
	{
		$data['PremiumCategories'] = Categories::where('delete_status', '0')->where('premium', '1')->orderby('id', 'desc')->get();
		$data['TopCategories'] = Categories::where('delete_status', '0')->where('top', '1')->orderby('id', 'desc')->get();
		$data['TrendingCategories'] = Categories::where('delete_status', '0')->where('trending', '1')->orderby('id', 'desc')->get();
		$data['allcategories'] = Categories::where('delete_status', '0')->orderby('name', 'ASC')->get();
		$data['Pages'] = Pages::where('delete_status', '0')->get();
		$data['city'] = City::where('delete_status', '0')->orderby('name', 'asc')->get();
		$userIp = $request->ip();
		$data['locationinfo'] = \Location::get($userIp);
		$adType = 'Paid';
		$data['jobads'] = Adposting::where('delete_status', '0')->where('status', '1')->orderByRaw("IF(ad_type = '{$adType}', ad_type, id) DESC")->get();
		$data['subscription'] = Subscription::where('delete_status', '0')->where('status', '0')->orderby('offered_price', 'DESC')->get();
		$data['homecategories'] = Categories::where('delete_status', '0')->limit(8)->get();
		$data['page'] = 'My Team';
		return view('website.my-team', $data);
	}



	public function category_details(Request $request, $id)
	{
		$data['PremiumCategories'] = Categories::where('delete_status', '0')->where('premium', '1')->orderby('id', 'desc')->get();
		$data['TopCategories'] = Categories::where('delete_status', '0')->where('top', '1')->orderby('id', 'desc')->get();
		$data['TrendingCategories'] = Categories::where('delete_status', '0')->where('trending', '1')->orderby('id', 'desc')->get();
		$data['allcategories'] = Categories::where('delete_status', '0')->orderby('name', 'ASC')->get();
		$data['allsubcategories'] = Subcategories::where('delete_status', '0')->where('category_id', $id)->get();
		$data['allpost'] = Jobforms::where('delete_status', '0')->where('category_id', $id)->get();
		$data['Pages'] = Pages::where('delete_status', '0')->get();
		$data['city'] = City::where('delete_status', '0')->orderby('name', 'asc')->get();
		$userIp = $request->ip();
		$data['locationinfo'] = \Location::get($userIp);
		$data['jobads'] = Jobforms::where('delete_status', '0')->where('status', '1')->orderby('id', 'desc')->get();
		$data['homecategories'] = Categories::where('delete_status', '0')->limit(8)->get();
		$data['page'] = 'Home';
		return view('website.all_ads', $data);
	}


	public function category_ads(Request $request, $id)
	{
		$data['PremiumCategories'] = Categories::where('delete_status', '0')->where('premium', '1')->orderby('id', 'desc')->get();
		$data['TopCategories'] = Categories::where('delete_status', '0')->where('top', '1')->orderby('id', 'desc')->get();
		$data['TrendingCategories'] = Categories::where('delete_status', '0')->where('trending', '1')->orderby('id', 'desc')->get();
		$data['allcategories'] = Categories::where('delete_status', '0')->orderby('name', 'ASC')->get();
		$data['allsubcategories'] = Subcategories::where('delete_status', '0')->where('category_id', $id)->get();
		//	$data['allpost']			= Adposting::where('ad_expiry' , '=', NULL)->where('status','1')->where('delete_status','0')->where('category_id',$id)->get();
		$adType = 'Paid';
		$data['allpost'] = Adposting::where('status', '1')->where('delete_status', '0')->where('category_id', $id)->whereDate('ad_expiry', '>', date('Y-m-d'))->orderByRaw("IF(ad_type = '{$adType}', ad_type, id) DESC")->get();
		$data['subscription'] = Subscription::where('delete_status', '0')->where('status', '0')->orderby('offered_price', 'DESC')->get();
		$data['Pages'] = Pages::where('delete_status', '0')->get();
		$data['city'] = City::where('delete_status', '0')->orderby('name', 'asc')->get();
		$userIp = $request->ip();
		$data['locationinfo'] = \Location::get($userIp);
		$data['jobads'] = Adposting::where('delete_status', '0')->where('status', '1')->orderByRaw("IF(ad_type = '{$adType}', ad_type, id) DESC")->whereDate('ad_expiry', '>', date('Y-m-d'))->get();
		$data['homecategories'] = Categories::where('delete_status', '0')->limit(8)->get();
		$data['page'] = 'Category Ads';
		return view('website.all_ads', $data);
	}

	public function subcategory_ads(Request $request, $id)
	{
		$data['PremiumCategories'] = Categories::where('delete_status', '0')->where('premium', '1')->orderby('id', 'desc')->get();
		$data['TopCategories'] = Categories::where('delete_status', '0')->where('top', '1')->orderby('id', 'desc')->get();
		$data['TrendingCategories'] = Categories::where('delete_status', '0')->where('trending', '1')->orderby('id', 'desc')->get();
		$data['allcategories'] = Categories::where('delete_status', '0')->orderby('name', 'ASC')->get();
		$data['allsubcategories'] = Subcategories::where('delete_status', '0')->where('id', $id)->get();
		$adType = 'Paid';
		$data['allpost'] = Adposting::where('delete_status', '0')->where('status', '1')->where('sub_category_id', $id)->whereDate('ad_expiry', '>', date('Y-m-d'))->orderByRaw("IF(ad_type = '{$adType}', ad_type, id) DESC")->get();
		$data['Pages'] = Pages::where('delete_status', '0')->get();
		$data['city'] = City::where('delete_status', '0')->orderby('name', 'asc')->get();
		$userIp = $request->ip();
		$data['locationinfo'] = \Location::get($userIp);
		$data['jobads'] = Adposting::where('delete_status', '0')->where('status', '1')->orderByRaw("IF(ad_type = '{$adType}', ad_type, id) DESC")->whereDate('ad_expiry', '>', date('Y-m-d'))->get();
		$data['homecategories'] = Categories::where('delete_status', '0')->limit(8)->get();
		$data['page'] = 'Category Ads';
		return view('website.all_ads', $data);
	}

	public function city_ads(Request $request)
	{
		$id = $request->city_id;
		$searchtxt = $request->search_txt;
		$data['PremiumCategories'] = Categories::where('delete_status', '0')->where('premium', '1')->orderby('id', 'desc')->get();
		$data['TopCategories'] = Categories::where('delete_status', '0')->where('top', '1')->orderby('id', 'desc')->get();
		$data['TrendingCategories'] = Categories::where('delete_status', '0')->where('trending', '1')->orderby('id', 'desc')->get();
		$data['allcategories'] = Categories::where('delete_status', '0')->orderby('name', 'ASC')->get();
		$data['allsubcategories'] = Subcategories::where('delete_status', '0')->where('id', $id)->get();
		$adType = 'Paid';
		$query = Adposting::where('status', '1')->where('delete_status', '0')->whereDate('ad_expiry', '>', date('Y-m-d'));

		if (!is_null($id)) {
			$query->where('city', $id);
		}
		if (!empty($searchtxt)) {
			$query->where(function ($q) use ($searchtxt) {
				$q->where('ad_title', 'like', '%' . $searchtxt . '%')
					->orWhere('description', 'like', '%' . $searchtxt . '%');
			});
		}
		$data['allpost'] = $query->orderByRaw("IF(ad_type = '{$adType}', ad_type, id) DESC")->get();
		$data['Pages'] = Pages::where('delete_status', '0')->get();
		$data['city'] = City::where('delete_status', '0')->orderby('name', 'asc')->get();
		$userIp = $request->ip();
		$data['locationinfo'] = \Location::get($userIp);
		$data['jobads'] = Adposting::where('delete_status', '0')->where('status', '1')->whereDate('ad_expiry', '>', date('Y-m-d'))->orderByRaw("IF(ad_type = '{$adType}', ad_type, id) DESC")->get();
		$data['homecategories'] = Categories::where('delete_status', '0')->limit(8)->get();
		$data['page'] = 'Searched Ads';
		return view('website.all_ads', $data);
	}

	public function category_location_ads(Request $request)
	{
		$category = $request->category;
		$city = $request->city;
		$data['PremiumCategories'] = Categories::where('delete_status', '0')->where('premium', '1')->orderby('id', 'desc')->get();
		$data['TopCategories'] = Categories::where('delete_status', '0')->where('top', '1')->orderby('id', 'desc')->get();
		$data['TrendingCategories'] = Categories::where('delete_status', '0')->where('trending', '1')->orderby('id', 'desc')->get();
		$data['allcategories'] = Categories::where('delete_status', '0')->where('id', $category)->orderby('name', 'ASC')->get();
		$data['allsubcategories'] = City::where('delete_status', '0')->where('id', $city)->get();
		$adType = 'Paid';
		$data['allpost'] = Adposting::where('status', '1')->where('delete_status', '0')->where('category_id', $category)->where('city', $city)->whereDate('ad_expiry', '>', date('Y-m-d'))->orderByRaw("IF(ad_type = '{$adType}', ad_type, id) DESC")->get();
		$data['Pages'] = Pages::where('delete_status', '0')->get();
		$data['city'] = City::where('delete_status', '0')->orderby('name', 'asc')->get();
		$userIp = $request->ip();
		$data['locationinfo'] = \Location::get($userIp);
		$data['jobads'] = Adposting::where('delete_status', '0')->where('status', '1')->whereDate('ad_expiry', '>', date('Y-m-d'))->orderByRaw("IF(ad_type = '{$adType}', ad_type, id) DESC")
			->orderByRaw("IF(ad_type = '{$adType}', ad_type, id) DESC")->get();
		$data['homecategories'] = Categories::where('delete_status', '0')->limit(8)->get();
		$data['page'] = 'Cityategory Ads';
		return view('website.all_ads', $data);
	}

	public function ads_details(Request $request, $id)
	{
		$data['PremiumCategories'] = Categories::where('delete_status', '0')->where('premium', '1')->orderby('id', 'desc')->get();
		$data['TopCategories'] = Categories::where('delete_status', '0')->where('top', '1')->orderby('id', 'desc')->get();
		$data['TrendingCategories'] = Categories::where('delete_status', '0')->where('trending', '1')->orderby('id', 'desc')->get();
		$data['allcategories'] = Categories::where('delete_status', '0')->orderby('name', 'ASC')->get();
		$data['Pages'] = Pages::where('delete_status', '0')->get();
		$data['city'] = City::where('delete_status', '0')->orderby('name', 'asc')->get();
		$userIp = $request->ip();
		$data['locationinfo'] = \Location::get($userIp);
		$data['page'] = 'Ads Details';
		$data['adsinfo'] = Adposting::find($id);
		$data['locationData'] = \Location::get($data['adsinfo']->location);
		if ($data['adsinfo']->formtype == 1) {
			$data['moreadsinfo'] = DB::table('jobforms')->where('ads_id', $data['adsinfo']->ad_id)->first();
		} else if ($data['adsinfo']->formtype == 2) {
			$data['moreadsinfo'] = DB::table('mobileforms')->where('ads_id', $data['adsinfo']->ad_id)->first();
		} else if ($data['adsinfo']->formtype == 3) {
			$data['moreadsinfo'] = DB::table('vehicleforms')->where('ads_id', $data['adsinfo']->ad_id)->first();
		} else if ($data['adsinfo']->formtype == 4) {
			$data['moreadsinfo'] = DB::table('propertyforms')->where('ads_id', $data['adsinfo']->ad_id)->first();
		} else if ($data['adsinfo']->formtype == 5) {
			$data['moreadsinfo'] = DB::table('commonforms')->where('ads_id', $data['adsinfo']->ad_id)->first();
		}
		$data['adsinfoimages'] = DB::table('ads_posting_images')->where('ads_id', $data['adsinfo']->ad_id)->get();
		$data['countadsinfoimages'] = DB::table('ads_posting_images')->where('ads_id', $data['adsinfo']->ad_id)->count();
		$data['category'] = Categories::find($data['adsinfo']->category_id);
		$data['subcategory'] = Subcategories::find($data['adsinfo']->sub_category_id);
		$result = DB::table('ads_postings')->where('id', $id)->get();
		$ctgid = $result['0']->category_id;
		$adType = 'Paid';
		$data['relatedpost'] = Adposting::with('ad_city.state.country')
			->where('delete_status', '0')
			->where('status', '1')
			->whereDate('ad_expiry', '>', date('Y-m-d'))
			->where('active_status', 1)
			->where('category_id', $ctgid)
			->where('id', '!=', $id) // Add this line to exclude the current record
			->limit(8)
			->orderByRaw("IF(ad_type = '{$adType}', ad_type, id) DESC")
			->get();

		$data['latestpost'] = Adposting::where('delete_status', '0')->where('status', '1')->where('category_id', $ctgid)->whereDate('ad_expiry', '>', date('Y-m-d'))->where('active_status', 1)->where('id', '!=', $id)->orderByRaw("IF(ad_type = '{$adType}', ad_type, id) DESC")->limit(3)->get();
		$user_id = session()->get('id');
		$session_id = session()->getId();
		$existingView = AdView::where('ad_id', $id)->where(function ($query) use ($user_id, $session_id) {
			$query->where('user_id', $user_id)
				->orWhere('session_id', $session_id);
		})->first();

		// If no existing view is found, create a new view record
		if (!$existingView) {
			AdView::create([
				'ad_id' => $id,
				'user_id' => $user_id,
				'session_id' => $session_id
			]);

			// Increment the ad view count
			$ad = Adposting::find($id);
			$ad->ad_view_count += 1;
			$ad->save();
		}


		$data['customer'] = Customer::with('subscriptionhistory')->findOrFail($data['adsinfo']->user_id);

		$data['enquiryExist'] = Adsenquiry::where('user_id', $user_id)->where('post_id', $id)->exists();

		return view('website.adsDetail', $data);
	}

	public function blogs(Request $request)
	{
		$data['PremiumCategories'] = Categories::where('delete_status', '0')->where('premium', '1')->orderby('id', 'desc')->get();
		$data['TopCategories'] = Categories::where('delete_status', '0')->where('top', '1')->orderby('id', 'desc')->get();
		$data['TrendingCategories'] = Categories::where('delete_status', '0')->where('trending', '1')->orderby('id', 'desc')->get();
		$data['allcategories'] = Categories::where('delete_status', '0')->orderby('name', 'ASC')->get();
		$data['blog'] = Blog::where('delete_status', '0')->orderby('id', 'desc')->get();
		$data['Pages'] = Pages::where('delete_status', '0')->get();
		$data['city'] = City::where('delete_status', '0')->orderby('name', 'asc')->get();
		$userIp = $request->ip();
		$data['locationinfo'] = \Location::get($userIp);
		$data['page'] = 'Blog';
		return view('website.blogs.blog', $data);
	}

	public function blog_details(Request $request, $id)
	{
		$data['PremiumCategories'] = Categories::where('delete_status', '0')->where('premium', '1')->orderby('id', 'desc')->get();
		$data['TopCategories'] = Categories::where('delete_status', '0')->where('top', '1')->orderby('id', 'desc')->get();
		$data['TrendingCategories'] = Categories::where('delete_status', '0')->where('trending', '1')->orderby('id', 'desc')->get();
		$data['allcategories'] = Categories::where('delete_status', '0')->orderby('name', 'ASC')->get();
		$data['Pages'] = Pages::where('delete_status', '0')->get();
		$data['city'] = City::where('delete_status', '0')->get();
		$userIp = $request->ip();
		$data['locationinfo'] = \Location::get($userIp);
		//$data['info']				= Pages::where('url',$url)->get();
		$data['blog'] = Blog::where('delete_status', '0')->orderby('id', 'desc')->limit(3)->get();
		$data['otherpost'] = Blog::where('delete_status', '0')->orderby('id', 'desc')->limit(5)->get();
		$data['bloginfo'] = Blog::find($id);
		$data['bloginfo']->view_count = $data['bloginfo']->view_count + 1;
		$data['bloginfo']->save();
		$data['page'] = 'Blog';
		return view('website.blogs.blogdetails', $data);
	}

	public function faqs(Request $request)
	{
		$data['PremiumCategories'] = Categories::where('delete_status', '0')->where('premium', '1')->orderby('id', 'desc')->get();
		$data['TopCategories'] = Categories::where('delete_status', '0')->where('top', '1')->orderby('id', 'desc')->get();
		$data['TrendingCategories'] = Categories::where('delete_status', '0')->where('trending', '1')->orderby('id', 'desc')->get();
		$data['allcategories'] = Categories::where('delete_status', '0')->orderby('name', 'ASC')->get();
		$data['faqCategories'] = Faqcategory::where('delete_status', '0')
			->whereHas('faqs', function ($query) {
				$query->where('delete_status', '0');
			})
			->with([
				'faqs' => function ($query) {
					$query->where('delete_status', '0');
				}
			])
			->orderBy('id', 'desc')
			->get();
		$data['Pages'] = Pages::where('delete_status', '0')->get();
		$data['city'] = City::where('delete_status', '0')->orderby('name', 'asc')->get();
		$userIp = $request->ip();
		$data['locationinfo'] = \Location::get($userIp);
		$data['page'] = 'Faq';
		return view('website.faq', $data);
	}

	public function contact(Request $request)
	{
		$data['PremiumCategories'] = Categories::where('delete_status', '0')->where('premium', '1')->orderby('id', 'desc')->get();
		$data['TopCategories'] = Categories::where('delete_status', '0')->where('top', '1')->orderby('id', 'desc')->get();
		$data['TrendingCategories'] = Categories::where('delete_status', '0')->where('trending', '1')->orderby('id', 'desc')->get();
		$data['allcategories'] = Categories::where('delete_status', '0')->orderby('name', 'ASC')->get();
		$data['contact'] = Contact::orderby('id', 'desc')->first();
		$data['Pages'] = Pages::where('delete_status', '0')->get();
		$data['city'] = City::where('delete_status', '0')->orderby('name', 'asc')->get();
		$userIp = $request->ip();
		$data['locationinfo'] = \Location::get($userIp);
		$data['page'] = 'Contact Us';

		return view('website.contact', $data);
	}


	public function abouts(Request $request)
	{
		$data['PremiumCategories'] = Categories::where('delete_status', '0')->where('premium', '1')->orderby('id', 'desc')->get();
		$data['TopCategories'] = Categories::where('delete_status', '0')->where('top', '1')->orderby('id', 'desc')->get();
		$data['TrendingCategories'] = Categories::where('delete_status', '0')->where('trending', '1')->orderby('id', 'desc')->get();
		$data['allcategories'] = Categories::where('delete_status', '0')->orderby('name', 'ASC')->get();
		$data['about'] = About::where('delete_status', '0')->orderby('id', 'desc')->get();
		$data['Pages'] = Pages::where('delete_status', '0')->get();
		$data['city'] = City::where('delete_status', '0')->orderby('name', 'asc')->get();
		$userIp = $request->ip();
		$data['locationinfo'] = \Location::get($userIp);
		$data['page'] = 'About';
		return view('website.abouts', $data);
	}

	public function all_product(Request $request)
	{
		$data['PremiumCategories'] = Categories::where('delete_status', '0')->where('premium', '1')->orderby('id', 'desc')->get();
		$data['TopCategories'] = Categories::where('delete_status', '0')->where('top', '1')->orderby('id', 'desc')->get();
		$data['TrendingCategories'] = Categories::where('delete_status', '0')->where('trending', '1')->orderby('id', 'desc')->get();
		$data['allcategories'] = Categories::where('delete_status', '0')->orderby('name', 'ASC')->get();
		$data['allsubcategories'] = Subcategories::where('delete_status', '0')->get();
		$data['about'] = About::where('delete_status', '0')->orderby('id', 'desc')->get();
		$data['Pages'] = Pages::where('delete_status', '0')->get();
		$data['city'] = City::where('delete_status', '0')->orderby('name', 'asc')->get();
		$userIp = $request->ip();
		$data['locationinfo'] = \Location::get($userIp);
		$data['page'] = 'All Ads';
		$data['jobads'] = Jobforms::where('delete_status', '0')->where('status', '1')->orderby('id', 'desc')->get();
		$data['homecategories'] = Categories::where('delete_status', '0')->limit(8)->get();
		$adType = 'Paid';
		$data['allpost'] = Adposting::where('status', '1')->where('delete_status', '0')->whereDate('ad_expiry', '>', date('Y-m-d'))->orderByRaw("IF(ad_type = '{$adType}', ad_type, id) DESC")->get();
		$data['subscription'] = Subscription::where('status', '1')->where('delete_status', '0')->orderBy('offered_price', 'desc')->get();
		return view('website.all-products', $data);
	}

	public function privacy_policy(Request $request)
	{
		$data['PremiumCategories'] = Categories::where('delete_status', '0')->where('premium', '1')->orderby('id', 'desc')->get();
		$data['TopCategories'] = Categories::where('delete_status', '0')->where('top', '1')->orderby('id', 'desc')->get();
		$data['TrendingCategories'] = Categories::where('delete_status', '0')->where('trending', '1')->orderby('id', 'desc')->get();
		$data['allcategories'] = Categories::where('delete_status', '0')->orderby('name', 'ASC')->get();
		$data['about'] = About::where('delete_status', '0')->orderby('id', 'desc')->get();
		$data['Pages'] = Pages::where('delete_status', '0')->get();
		$data['city'] = City::where('delete_status', '0')->orderby('name', 'asc')->get();
		$userIp = $request->ip();
		$data['locationinfo'] = \Location::get($userIp);
		$data['page'] = 'Privacy Policy';
		return view('website.privacy_policy', $data);
	}

	public function term_conditions(Request $request)
	{
		$data['PremiumCategories'] = Categories::where('delete_status', '0')->where('premium', '1')->orderby('id', 'desc')->get();
		$data['TopCategories'] = Categories::where('delete_status', '0')->where('top', '1')->orderby('id', 'desc')->get();
		$data['TrendingCategories'] = Categories::where('delete_status', '0')->where('trending', '1')->orderby('id', 'desc')->get();
		$data['allcategories'] = Categories::where('delete_status', '0')->orderby('name', 'ASC')->get();
		$data['about'] = About::where('delete_status', '0')->orderby('id', 'desc')->get();
		$data['Pages'] = Pages::where('delete_status', '0')->get();
		$data['city'] = City::where('delete_status', '0')->orderby('name', 'asc')->get();
		$userIp = $request->ip();
		$data['locationinfo'] = \Location::get($userIp);
		$data['page'] = 'Terms Conditions';
		return view('website.term_conditions', $data);
	}

	public function pages(Request $request, $id, $url)
	{
		$data['PremiumCategories'] = Categories::where('delete_status', '0')->where('premium', '1')->orderby('id', 'desc')->get();
		$data['TopCategories'] = Categories::where('delete_status', '0')->where('top', '1')->orderby('id', 'desc')->get();
		$data['TrendingCategories'] = Categories::where('delete_status', '0')->where('trending', '1')->orderby('id', 'desc')->get();
		$data['allcategories'] = Categories::where('delete_status', '0')->orderby('name', 'ASC')->get();
		$data['Pages'] = Pages::where('delete_status', '0')->get();
		$data['city'] = City::where('delete_status', '0')->orderby('name', 'asc')->get();
		$userIp = $request->ip();
		$data['locationinfo'] = \Location::get($userIp);
		//$data['info']				= Pages::where('url',$url)->get();
		$data['info'] = Pages::find($id);
		$data['page'] = $url;
		return view('website.pages', $data);
	}



	public function notification(Request $request)
	{
		$cust = Customer::find(session()->has('id'));
		if (session()->has('id') && !empty($cust)) {
			$user_id = session('id');
			$data['PremiumCategories'] = Categories::where('delete_status', '0')->where('premium', '1')->orderby('id', 'desc')->get();
			$data['TopCategories'] = Categories::where('delete_status', '0')->where('top', '1')->orderby('id', 'desc')->get();
			$data['TrendingCategories'] = Categories::where('delete_status', '0')->where('trending', '1')->orderby('id', 'desc')->get();
			$data['allcategories'] = Categories::where('delete_status', '0')->orderby('name', 'ASC')->get();
			$data['Pages'] = Pages::where('delete_status', '0')->get();
			$data['city'] = City::where('delete_status', '0')->orderby('name', 'asc')->get();
			$data['countries'] = Countries::where('delete_status', '0')->orderby('id', 'desc')->get();
			$data['states'] = States::where('delete_status', '0')->orderby('id', 'desc')->get();
			$data['zip'] = Zip::where('delete_status', '0')->orderby('id', 'desc')->get();
			$userIp = $request->ip();
			$data['locationinfo'] = \Location::get($userIp);
			$data['page'] = 'Notification';
			$data['customerinfo'] = Customer::find($user_id);
			$adType = 'Paid';
			$data['myads'] = Adposting::where('delete_status', '0')->where('user_id', $user_id)->orderByRaw("IF(ad_type = '{$adType}', ad_type, id) DESC")->get();
			$data['myadsenquiry'] = Adsenquiry::where('user_id', $user_id)->orderby('id', 'desc')->get();
			return view('website.notification', $data);
		} else {
			return redirect()->route('login');
		}
	}

	public function user_dashboard(Request $request)
	{
		$cust = Customer::find(session()->has('id'));
		if (session()->has('id') && !empty($cust)) {
			$user_id = session('id');
			$data['PremiumCategories'] = Categories::where('delete_status', '0')->where('premium', '1')->orderby('id', 'desc')->get();
			$data['TopCategories'] = Categories::where('delete_status', '0')->where('top', '1')->orderby('id', 'desc')->get();
			$data['TrendingCategories'] = Categories::where('delete_status', '0')->where('trending', '1')->orderby('id', 'desc')->get();
			$data['allcategories'] = Categories::where('delete_status', '0')->orderby('name', 'ASC')->get();
			$data['Pages'] = Pages::where('delete_status', '0')->get();
			$data['city'] = City::where('delete_status', '0')->orderby('name', 'asc')->get();
			$data['countries'] = Countries::where('delete_status', '0')->orderby('id', 'desc')->get();
			$data['states'] = States::where('delete_status', '0')->orderby('id', 'desc')->get();
			$data['zip'] = Zip::where('delete_status', '0')->orderby('id', 'desc')->get();
			$userIp = $request->ip();
			$data['locationinfo'] = \Location::get($userIp);
			$data['page'] = 'Home';
			$data['customerinfo'] = Customer::find($user_id);
			$data['adminsetting'] = Adminsettings::first();
			$adType = 'Paid';
			$data['myads'] = Adposting::where('delete_status', '0')->where('user_id', $user_id)->orderByRaw("IF(ad_type = '{$adType}', ad_type, id) DESC")->get();
			$data['myadsenquiry'] = Adsenquiry::where('user_id', $user_id)->orderby('id', 'desc')->get();

			return view('website.dashboard', $data);

		} else {
			return redirect()->route('login');
		}
	}

	public function user_profile(Request $request)
	{
		$cust = Customer::find(session()->has('id'));
		if (session()->has('id') && !empty($cust)) {
			$user_id = session('id');
			$data['PremiumCategories'] = Categories::where('delete_status', '0')->where('premium', '1')->orderby('id', 'desc')->get();
			$data['TopCategories'] = Categories::where('delete_status', '0')->where('top', '1')->orderby('id', 'desc')->get();
			$data['TrendingCategories'] = Categories::where('delete_status', '0')->where('trending', '1')->orderby('id', 'desc')->get();
			$data['allcategories'] = Categories::where('delete_status', '0')->orderby('name', 'ASC')->get();
			$data['Pages'] = Pages::where('delete_status', '0')->get();
			$data['city'] = City::where('delete_status', '0')->orderby('name', 'asc')->get();
			$data['countries'] = Countries::where('delete_status', '0')->orderby('id', 'desc')->get();
			$data['states'] = States::where('delete_status', '0')->orderby('id', 'desc')->get();
			$data['zip'] = Zip::where('delete_status', '0')->orderby('id', 'desc')->get();
			$userIp = $request->ip();
			$data['locationinfo'] = \Location::get($userIp);
			$data['page'] = 'Profile';
			$data['customerinfo'] = Customer::find($user_id);
			$adType = 'Paid';
			$data['myads'] = Adposting::where('delete_status', '0')->where('user_id', $user_id)->orderByRaw("IF(ad_type = '{$adType}', ad_type, id) DESC")->get();
			$data['myadsenquiry'] = Adsenquiry::where('user_id', $user_id)->orderby('id', 'desc')->get();
			return view('website.profile', $data);
		} else {
			return redirect()->route('login');
		}
	}

	public function user_profile_account(Request $request)
	{
		//dd($request->all());
		$cust = Customer::find(session()->has('id'));
		if (session()->has('id') && !empty($cust)) {
			if (isset($request->account_number) || isset($request->confirm_acct_num)) {
				if ($request->account_number != $request->confirm_acct_num) {
					return redirect("user-profile")->withErrors(['error' => 'Please Enter The Account Details Correctly']);
				}
			}
			$user_id = session('id');
			$userprofile = Customer::find($user_id);
			$userprofile->name = $request->name;
			$userprofile->gender = $request->gender;
			$userprofile->dob = $request->dob;
			$userprofile->address = $request->address;
			$userprofile->country = $request->country;
			$userprofile->user_type = 'Free';
			$userprofile->country = $request->country;
			$userprofile->state = $request->state;
			$userprofile->city = $request->city;
			$userprofile->pin = $request->pin;
			$userprofile->introduction = $request->introduction;
			$userprofile->website = $request->website;
			$userprofile->youtube = $request->youtube;
			$userprofile->facebook = $request->facebook;
			$userprofile->twitter = $request->twitter;
			$userprofile->whatsapp = $request->whatsapp;

			$userprofile->adhar_number = $request->adhar_number;
			$userprofile->pancard_num = $request->pancard_num;
			$userprofile->bank_name = $request->bank_name;
			$userprofile->bank_branch = $request->bank_branch;
			$userprofile->account_name = $request->account_name;
			$userprofile->confirm_acct_num = $request->confirm_acct_num;
			$userprofile->account_number = $request->account_number;
			$userprofile->account_ifsc = $request->account_ifsc;
			$userprofile->upi_id = $request->upi_id;

			if (request()->hasFile('image')) {
				$image = $request->file('image');
				$images = "image" . time() . '.' . $image->getClientOriginalExtension();
				$destinationPath = public_path('/admin/images');
				$image->move($destinationPath, $images);
				$userprofile->image = url('public/admin/images/' . $images);

			}

			if (request()->hasFile('aadharfront')) {
				$aadharfront = $request->file('aadharfront');
				$aadharfronts = "ad" . time() . '.' . $aadharfront->getClientOriginalExtension();
				$destinationPath1 = public_path('/admin/images');
				$aadharfront->move($destinationPath1, $aadharfronts);
				$userprofile->aadharfronts = $aadharfronts;

			}

			if (request()->hasFile('qr_code_image')) {
				$qr_code_image = $request->file('qr_code_image');
				$qr_code_images = "ad" . time() . '.' . $qr_code_image->getClientOriginalExtension();
				$destinationPath5 = public_path('/admin/images');
				$qr_code_image->move($destinationPath5, $qr_code_images);
				$userprofile->qr_code_image = $qr_code_images;

			}



			if (request()->hasFile('aadharback')) {
				$aadharback = $request->file('aadharback');
				$aadharbacks = "adb" . time() . '.' . $aadharback->getClientOriginalExtension();
				$destinationPath2 = public_path('/admin/images');
				$aadharback->move($destinationPath2, $aadharbacks);
				$userprofile->aadharback = $aadharbacks;
			}


			if (request()->hasFile('pancard')) {
				$pancard = $request->file('pancard');
				$pancards = "pan" . time() . '.' . $pancard->getClientOriginalExtension();
				$destinationPath3 = public_path('/admin/images');
				$pancard->move($destinationPath3, $pancards);
				$userprofile->pancard = $pancards;
			}
			if (request()->hasFile('cheque')) {
				$cheque = $request->file('cheque');
				$cheques = "pan" . time() . '.' . $cheque->getClientOriginalExtension();
				$destinationPath4 = public_path('/admin/images');
				$cheque->move($destinationPath4, $cheques);
				$userprofile->cheque = $cheques;
			}

			$userprofile->save();
			\Session::put('success', 'Profile Update Successfully.');
			return redirect("user-profile");
		} else {
			return redirect()->route('login');
		}
	}

	public function user_dash(Request $request)
	{
		$cust = Customer::find(session()->has('id'));
		if (session()->has('id') && !empty($cust)) {
			$user_id = session('id');
			$data['PremiumCategories'] = Categories::where('delete_status', '0')->where('premium', '1')->orderby('id', 'desc')->get();
			$data['TopCategories'] = Categories::where('delete_status', '0')->where('top', '1')->orderby('id', 'desc')->get();
			$data['TrendingCategories'] = Categories::where('delete_status', '0')->where('trending', '1')->orderby('id', 'desc')->get();
			$data['allcategories'] = Categories::where('delete_status', '0')->orderby('name', 'ASC')->get();
			$data['Pages'] = Pages::where('delete_status', '0')->get();
			$data['city'] = City::where('delete_status', '0')->orderby('name', 'asc')->get();
			$data['countries'] = Countries::where('delete_status', '0')->orderby('id', 'desc')->get();
			$data['states'] = States::where('delete_status', '0')->orderby('id', 'desc')->get();
			$data['zip'] = Zip::where('delete_status', '0')->orderby('id', 'desc')->get();
			$userIp = $request->ip();
			$data['locationinfo'] = \Location::get($userIp);
			$data['page'] = 'Dashboard';
			$data['customerinfo'] = Customer::find($user_id);
			$adType = 'Paid';
			$data['myads'] = Adposting::where('delete_status', '0')->where('user_id', $user_id)->orderByRaw("IF(ad_type = '{$adType}', ad_type, id) DESC")->get();
			$data['myadsenquiry'] = Adsenquiry::where('user_id', $user_id)->orderby('id', 'desc')->get();
			$data['count_active_ads'] = DB::table('ads_postings')->where('user_id', $user_id)->where('status', 1)->where('delete_status', 0)->count('id');
			$data['count_panding_ads'] = DB::table('ads_postings')->where('user_id', $user_id)->where('status', 0)->where('delete_status', 0)->count('id');
			$data['count_rejected_ads'] = DB::table('ads_postings')->where('user_id', $user_id)->where('status', 2)->where('delete_status', 0)->count('id');
			$data['count_expire_cat'] = DB::table('ads_postings')->where('user_id', $user_id)->where('status', 3)->where('delete_status', 0)->count('id');
			$data['count_ads_enquiry'] = Adsenquiry::where('user_id', $user_id)->orderby('id', 'desc')->count();
			// $data['remaining_ads'] = DB::table('subscription_history')->where('user_id',$user_id)->sum('remaining_ads');
			// $data['used_ads'] = DB::table('subscription_history')->where('user_id',$user_id)->sum('used_ads');
			$data['remaining_ads'] = DB::table('subscription_orders')->where('user_id', $user_id)->where('payment_status', 'completed')->sum('remaining_ads');
			$data['subscription_exists'] = DB::table('subscription_history')->where('user_id', $user_id)->whereDate('subscription_expiry', '>=', date('Y-m-d'))->where('delete_status', 0)->exists();
			$data['total_active_seeds'] = DB::table('subscription_history')->where('user_id', $user_id)->whereDate('subscription_expiry', '>=', date('Y-m-d'))->where('delete_status', 0)->sum('total_joined');
			$data['total_active_subscription'] = DB::table('subscription_history')->where('user_id', $user_id)->whereDate('subscription_expiry', '>=', date('Y-m-d'))->where('delete_status', 0)->count();
			$data['used_ads'] = Adposting::where('delete_status', '0')->where('user_id', $user_id)->where('active_status', 1)->count('id');
			$releasedEarn = CustomerCommission::where('parent_id', session('id'))->whereYear('created_at', now()->year)->where('status', 'approved')->get();
			if ($releasedEarn->count() > 0) {
				$data['releasedEarning'] = $releasedEarn->sum('total_earned');
			}
			//$data['history'] 	= DB::table('subscription_orders')->where('user_id',$user_id)->where('delete_status',0)->orderby('id','desc')->get();
			$data['expiry_history'] = DB::table('subscription_orders')->where('user_id', $user_id)->where('delete_status', 0)->orderby('created_at', 'desc')->first();
			return view('website.user-dash', $data);
		} else {
			return redirect()->route('login');
		}
	}

	public function changeEmail(Request $request)
	{
		//dd($request->all());
		$request->validate([
			'currentEmail' => 'required|email',
			'newEmail' => 'required|email|unique:customers,email',
			'currentPassword' => 'required',
		]);

		$customer = Customer::where('email', $request->currentEmail)->first();

		if (!$customer || !Hash::check($request->currentPassword, $customer->password)) {
			return response()->json(['message' => 'Current password is incorrect.'], 400);
		}

		if ($request->has('otp')) {
			$sessionOtp = session('email_otp');
			if ($request->otp == $sessionOtp) {
				$customer->email = $request->newEmail;
				$customer->save();
				session()->forget(['email_otp', 'new_email']);
				return response()->json(['message' => 'Email updated successfully.']);
			} else {
				return response()->json(['message' => 'Invalid OTP.'], 400);
			}
		} else {
			$otp = rand(100000, 999999);
			$userName = $customer->name;
			session(['email_otp' => $otp, 'new_email' => $request->newEmail]);
			Mail::to($request->newEmail)->send(new OtpEmailNotification($otp, $userName));
			return response()->json(['message' => 'OTP sent to your email.', 'otp' => $otp]);
		}
	}



	public function change_password(Request $request)
	{
		$cust = Customer::find(session()->has('id'));
		if (session()->has('id') && !empty($cust)) {
			$user_id = session('id');
			$data['city'] = City::where('delete_status', '0')->get();
			$data['page'] = 'Change Password';
			return view('website.change-password', $data);
		} else {
			return redirect()->route('login');
		}
	}

	public function user_pswd_change(Request $request)
	{
		$cust = Customer::find(session()->has('id'));
		if (session()->has('id') && !empty($cust)) {
			$user_id = session('id');
			$old_pswd = $request->old_password;
			$new_pswd = $request->new_password;
			$confirm_pswd = $request->confirm_password;

			$customerinfo = Customer::find($user_id);
			if ($customerinfo && Hash::check($old_pswd, $customerinfo->password)) {
				if ($new_pswd == $confirm_pswd) {
					// Update the password
					$customerinfo->password = bcrypt($new_pswd); // Hash the new password
					$customerinfo->save();

					// Redirect with success message
					return redirect("change-password")->with('success', "Password changed successfully.");
				} else {
					// Redirect with error message
					return redirect("change-password")->with('error', "New Password and Confirm Password don't match.");
				}
			} else {
				// Redirect with error message
				return redirect("change-password")->with('error', "Old Password doesn't match. Please enter the correct password.");
			}
		} else {
			// Redirect to login if user is not authenticated
			return redirect()->route('login');
		}
	}

	public function privacy_setting(Request $request)
	{
		$cust = Customer::find(session()->has('id'));
		if (session()->has('id') && !empty($cust)) {
			$user_id = session('id');
			$data['city'] = City::where('delete_status', '0')->orderby('name', 'asc')->get();
			$data['page'] = 'Privacy Setting';
			return view('website.privacy-setting', $data);
		} else {
			return redirect()->route('login');
		}
	}
	public function close_account(Request $request)
	{
		$cust = Customer::find(session()->has('id'));
		if (session()->has('id') && !empty($cust)) {
			$user_id = session('id');
			$data['city'] = City::where('delete_status', '0')->get();
			$data['page'] = 'Close Account';
			return view('website.close-account', $data);
		} else {
			return redirect()->route('login');
		}
	}

	public function deactivate_account(Request $request)
	{
		$cust = Customer::find(session()->has('id'));
		if (session()->has('id') && !empty($cust)) {
			$user_id = session('id');
			$old_pswd = $request->old_password;
			$user = Customer::find($user_id);

			if ($user && Hash::check($old_pswd, $user->password)) {
				$user->delete_status = 1;
				$user->deleted_at = now();
				$user->save();
				$adType = 'Paid';
				$adsposting = Adposting::where('user_id', $user_id)->orderByRaw("IF(ad_type = '{$adType}', ad_type, id) DESC")->get();
				if (isset($adsposting) && count($adsposting) > 0) {
					Adposting::where('user_id', $user_id)->update(['status' => 0]);
				}

				$customerChild = Customer_child::where('user_id', $user_id)->get();
				if (isset($customerChild) && count($customerChild) > 0) {
					$customerChild->update(['status' => 'Removed']);
				}
				// Assuming ActivationAccount is a valid Mailable class
				$mailData = []; // Populate with necessary data if needed
				Mail::to($user->email)->send(new ActivationAccount($mailData));
				\Session::put('success', "Account has been deactivated. You can activate your account within 30 days. After 30 days, your account will be permanently closed.");

				Auth::logout();
				Session::flush();

				return redirect(url('login'))->with(['success' => "Account has been deactivated. You can activate your account within 30 days. After 30 days, your account will be permanently closed."]);
			} else {
				\Session::put('error', "Old password doesn't match. Please enter the correct password.");
				return redirect("close-account");
			}
		} else {
			return redirect()->route('login');
		}

	}
	public function logout_alldevice(Request $request)
	{
		$cust = Customer::find(session()->has('id'));
		if (session()->has('id') && !empty($cust)) {
			$user_id = session('id');
			$data['page'] = 'Logout All Device';
			return view('website.logout-alldevice', $data);
		} else {
			return redirect()->route('login');
		}
	}

	public function device_logout(Request $request)
	{
		$cust = Customer::find(session()->has('id'));
		if (session()->has('id') && !empty($cust)) {
			$user_id = session('id');
			$old_pswd = $request->input('old_pswd');

			$user = Customer::find($user_id);

			if ($user && Hash::check($old_pswd, $user->password)) {
				Session::flush();
				return redirect()->route('login');
			} else {
				// Set an error message and redirect back
				Session::put('error', "Current Password doesn't match. Please enter the correct password.");
				return redirect()->route('logout-alldevice');
			}
		} else {
			return redirect()->route('login');
		}
	}
	public function notifications(Request $request)
	{
		$cust = Customer::find(session()->has('id'));
		if (session()->has('id') && !empty($cust)) {
			$user_id = session('id');
			$data['user_id'] = $user_id;
			CustomNotificationHistory::where('customer_id', $user_id)->update(['read_at' => date('Y-m-d H:i:s')]);
			DefaultNotificationHistory::where('customer_id', $user_id)->update(['read_at' => date('Y-m-d H:i:s')]);
			$customeNotification = CustomNotificationHistory::where('customer_id', $user_id)->get();
			$defaultNotification = DefaultNotificationHistory::where('customer_id', $user_id)->get();
			$customerNotfications = $customeNotification->merge($defaultNotification)->sortByDesc('created_at');

			$data['PremiumCategories'] = Categories::where('delete_status', '0')->where('premium', '1')->orderby('id', 'desc')->get();
			$data['TopCategories'] = Categories::where('delete_status', '0')->where('top', '1')->orderby('id', 'desc')->get();
			$data['TrendingCategories'] = Categories::where('delete_status', '0')->where('trending', '1')->orderby('id', 'desc')->get();
			$data['allcategories'] = Categories::where('delete_status', '0')->orderby('name', 'ASC')->get();
			$data['Pages'] = Pages::where('delete_status', '0')->get();
			$data['city'] = City::where('delete_status', '0')->orderby('name', 'asc')->get();
			$data['countries'] = Countries::where('delete_status', '0')->orderby('id', 'desc')->get();
			$data['states'] = States::where('delete_status', '0')->orderby('id', 'desc')->get();
			$data['zip'] = Zip::where('delete_status', '0')->orderby('id', 'desc')->get();
			$userIp = $request->ip();
			$data['locationinfo'] = \Location::get($userIp);
			$data['page'] = 'Notifications';
			$adType = 'Paid';
			$data['my_ads'] = Adposting::where('user_id', $user_id)->where('delete_status', 0)->orderByRaw("IF(ad_type = '{$adType}', ad_type, id) DESC")->get();
			$data['history'] = DB::table('subscription_orders')->where('user_id', $user_id)->where('delete_status', 0)->orderby('id', 'desc')->get();
			$data['remaining_ads'] = DB::table('subscription_history')->where('user_id', $user_id)->where('subscription_expiry', '>=', Carbon::now())->sum('remaining_ads');
			$data['subscription_exists'] = DB::table('subscription_history')->where('user_id', $user_id)->whereDate('subscription_expiry', '>=', date('Y-m-d'))->exists();
			$data['subscription_bucket'] = SubscriptionHistory::with('subscriptions')->where('user_id', $user_id)->whereDate('subscription_expiry', '>=', date('Y-m-d'))->where('delete_status', 0)->get();
			//$data['history'] 	= DB::table('subscription_orders')->where('user_id',$user_id)->where('delete_status',0)->orderby('id','desc')->get();
			$data['expiry_history'] = DB::table('subscription_orders')->where('user_id', $user_id)->where('delete_status', 0)->orderby('created_at', 'desc')->first();
			$c = collect($customerNotfications);
			$items = $c->all();
			ksort($items);
			$c = collect($items);
			$c->sort();
			$data['notificationData'] = $c;

			return view('website.notifications', $data);
		} else {
			return redirect()->route('login');
		}
	}

	public function my_ads(Request $request)
	{
		$cust = Customer::find(session()->has('id'));
		if (session()->has('id') && !empty($cust)) {
			$user_id = session('id');
			$data['user_id'] = $user_id;
			$data['PremiumCategories'] = Categories::where('delete_status', '0')->where('premium', '1')->orderby('id', 'desc')->get();
			$data['TopCategories'] = Categories::where('delete_status', '0')->where('top', '1')->orderby('id', 'desc')->get();
			$data['TrendingCategories'] = Categories::where('delete_status', '0')->where('trending', '1')->orderby('id', 'desc')->get();
			$data['allcategories'] = Categories::where('delete_status', '0')->orderby('name', 'ASC')->get();
			$data['Pages'] = Pages::where('delete_status', '0')->get();
			$data['city'] = City::where('delete_status', '0')->orderby('name', 'asc')->get();
			$data['countries'] = Countries::where('delete_status', '0')->orderby('id', 'desc')->get();
			$data['states'] = States::where('delete_status', '0')->orderby('id', 'desc')->get();
			$data['zip'] = Zip::where('delete_status', '0')->orderby('id', 'desc')->get();
			$userIp = $request->ip();
			$data['locationinfo'] = \Location::get($userIp);
			$data['page'] = 'My Ads';
			$adType = 'Paid';
			$data['my_ads'] = Adposting::where('user_id', $user_id)->where('delete_status', 0)->orderByRaw("IF(ad_type = '{$adType}', ad_type, id) DESC")->get();
			$data['history'] = DB::table('subscription_orders')->where('user_id', $user_id)->where('delete_status', 0)->orderby('id', 'desc')->get();
			$data['remaining_ads'] = DB::table('subscription_history')->where('user_id', $user_id)->where('subscription_expiry', '>=', Carbon::now())->sum('remaining_ads');
			$data['subscription_exists'] = DB::table('subscription_history')->where('user_id', $user_id)->whereDate('subscription_expiry', '>=', date('Y-m-d'))->exists();
			$data['subscription_bucket'] = SubscriptionHistory::with('subscriptions')->where('user_id', $user_id)->whereDate('subscription_expiry', '>=', date('Y-m-d'))->where('delete_status', 0)->get();
			//$data['history'] 	= DB::table('subscription_orders')->where('user_id',$user_id)->where('delete_status',0)->orderby('id','desc')->get();
			$data['expiry_history'] = DB::table('subscription_orders')->where('user_id', $user_id)->where('delete_status', 0)->orderby('created_at', 'desc')->first();
			return view('website.my-ads', $data);
		} else {
			return redirect()->route('login');
		}
	}

	public function my_ads_enquiry(Request $request)
	{
		$cust = Customer::find(session()->has('id'));
		if (session()->has('id') && !empty($cust)) {
			$user_id = session('id');
			$data['PremiumCategories'] = Categories::where('delete_status', '0')->where('premium', '1')->orderby('id', 'desc')->get();
			$data['TopCategories'] = Categories::where('delete_status', '0')->where('top', '1')->orderby('id', 'desc')->get();
			$data['TrendingCategories'] = Categories::where('delete_status', '0')->where('trending', '1')->orderby('id', 'desc')->get();
			$data['allcategories'] = Categories::where('delete_status', '0')->orderby('name', 'ASC')->get();
			$data['Pages'] = Pages::where('delete_status', '0')->get();
			$data['city'] = City::where('delete_status', '0')->orderby('name', 'asc')->get();
			$data['countries'] = Countries::where('delete_status', '0')->orderby('id', 'desc')->get();
			$data['states'] = States::where('delete_status', '0')->orderby('id', 'desc')->get();
			$data['zip'] = Zip::where('delete_status', '0')->orderby('id', 'desc')->get();
			$userIp = $request->ip();
			$data['locationinfo'] = \Location::get($userIp);
			$data['page'] = 'Sent Enquiry';
			$data['city'] = City::where('delete_status', '0')->orderby('name', 'asc')->get();
			$data['my_ads'] = DB::table('ads_postings')->where('user_id', $user_id)->where('delete_status', 0)->orderby('id', 'desc')->get();
			$data['my_enquiry'] = DB::table('ads_enquiries')->where('user_id', $user_id)->orderby('id', 'desc')->get();
			return view('website.my-enquiry', $data);
		} else {
			return redirect()->route('login');
		}
	}

	public function owner_enquiry(Request $request)
	{
		$cust = Customer::find(session()->has('id'));
		if (session()->has('id') && !empty($cust)) {
			$user_id = session('id');
			$data['PremiumCategories'] = Categories::where('delete_status', '0')->where('premium', '1')->orderby('id', 'desc')->get();
			$data['TopCategories'] = Categories::where('delete_status', '0')->where('top', '1')->orderby('id', 'desc')->get();
			$data['TrendingCategories'] = Categories::where('delete_status', '0')->where('trending', '1')->orderby('id', 'desc')->get();
			$data['allcategories'] = Categories::where('delete_status', '0')->orderby('name', 'ASC')->get();
			$data['Pages'] = Pages::where('delete_status', '0')->get();
			$data['page'] = 'Owner Enquiry';
			$data['my_ads'] = DB::table('ads_postings')->where('user_id', $user_id)->where('delete_status', 0)->orderby('id', 'desc')->get();
			//	$data['my_enquiry'] = DB::table('ads_enquiries')->where('post_id',$my_ads->id)->orderby('id','desc')->get();

			return view('website.owner-enquiry', $data);
		} else {
			return redirect()->route('login');
		}
	}

	public function get_my_ads(Request $request)
	{
		$id = $request->id;
		$user_id = session('id');
		if ($id == 'active') {
			$ads = DB::table('ads_postings')->where('user_id', $user_id)->where('status', 1)->where('delete_status', 0)->orderby('id', 'desc')->get();

		} else if ($id == 'pending') {
			$ads = DB::table('ads_postings')->where('user_id', $user_id)->where('status', 0)->where('delete_status', 0)->orderby('id', 'desc')->get();

		} else if ($id == 'expire') {
			$ads = DB::table('ads_postings')->where('user_id', $user_id)->where('status', 3)->where('delete_status', 0)->orderby('id', 'desc')->get();

		} else if ($id == 'reject') {
			$ads = DB::table('ads_postings')->where('user_id', $user_id)->where('status', 2)->where('delete_status', 0)->orderby('id', 'desc')->get();
		} else {
			$ads = DB::table('ads_postings')->where('user_id', $user_id)->where('delete_status', 0)->orderby('id', 'desc')->get();
		}

		$html = array();
		if (isset($ads) && count($ads) > 0) {
			foreach ($ads as $index => $ads) {
				$i = $index + 1;
				if ($ads->status == 0) {
					$status = 'Pending';
				} else if ($ads->status == 1) {
					$status = 'Active';
				} else if ($ads->status == 2) {
					$status = 'Reject';
				} else {
					$status = 'Expire';
				}
				$html[] = '<tr>
                <th scope="row">' . $ads->created_at . '</th>
                <td><img src="' . $ads->image . '" width="60px"></td>
                <td>' . $ads->ad_title . '</td>
                <td>' . $ads->published_date . '</td>
                <td>' . $ads->ad_expiry . '</td>
                <td>' . $ads->subscription_id . '</td>
                <td>' . $status . '</td>
                <td><a title="Edit" href="' . url('edit-ads', [encrypt($ads->formtype), encrypt($ads->category_id), encrypt($ads->id)]) . '"><i class="material-icons">edit</i></a>
                        <a title="View" class="btn-view" data_id="' . $ads->id . '" href="javascript:void(0)"><i class="material-icons">visibility</i></a>
                        <a title="Delete" class="btn-delete" href="javascript:void(0)" data-href="' . url('delete-ads', $ads->id) . '"><i class="material-icons">delete</i></a>
                        <a title="Connect to Chat" href="' . url('user-chat') . '"><i class="material-icons">chat</i></a></td>
            </tr>' . ($ads->status == 2 && !is_null($ads->reason) ? '<tr>
                                    <td colspan="9">
                                        <div class="rejection-note">
                                            <strong>Rejection Note:</strong> ' . $ads->reason . '
                                        </div>
                                    </td>
                                </tr>' : '');


			}
		} else {
			$html = '<tr>
    					<td colspan="9">No Ads Found</td>
    				
    		</tr>';
		}
		return response()->json($html);
	}
	public function delete_ads($id)
	{
		$user_id = session('id');

		$ads = DB::table('ads_postings')->where('user_id', $user_id)->where('id', $id)->update(['delete_status' => '1']);
		$ad = Adposting::where('user_id', $user_id)->where('id', $id)->first();
		if ($ad->active_status == 1) {
			DB::table('subscription_history')
				->where('id', $ad->subscription_id)
				->where('user_id', $user_id)
				->where('status', '0')
				->where('subscription_expiry', '>', Carbon::now())
				->decrement('used_ads', 1);
		}
		return response()->json([
			'success' => true,
			'message' => 'Deleted Successfully!',
		]);
	}

	public function get_my_subscription(Request $request)
	{
		$id = $request->id;
		$user_id = session('id');
		$isAutoJoin = SubscriptionHistory::where('user_id', session('id'))
			->where('status', '0')
			->where('auto_join', '1')
			->where('subscription_expiry', '>', date("d-m-Y"))
			->where('delete_status', '0')->exists();
		if ($isAutoJoin) {
			$joinText = ' <td> ' . $subh->auto_join_member . '</td>';
		} else {
			$joinText = '';
		}
		$html = [];
		if ($id == 'active') {
			$history = DB::table('subscription_orders')->where('user_id', $user_id)->where('delete_status', 0)->orderby('id', 'desc')->get();

			if (isset($history)) {
				foreach ($history as $index => $subh) {
					$i = $index + 1;
					$result = DB::table('subscriptions')->where('id', $subh->subscription_id)->first();
					$status_dates = date("d-m-Y");
					$no = explode(" ", $result->package_validity);
					$nos = $no[0] + 1;


					$dates = $subh->created_at;
					$date = date_create($dates);
					date_add($date, date_interval_create_from_date_string($nos . "days"));
					$subscription_expiry = date_format($date, "d-m-Y");
					$subs_date = explode(" ", $subh->created_at);
					$dats_subs = date_create($subs_date[0]);
					$time_subs = date_create($subs_date[1]);

					$cal_date = date_format($dats_subs, "d-m-Y");

					$final_date = date_format($dats_subs, "d-m-Y") . '|' . date_format($time_subs, "H:i A");

					if (strtotime($status_dates) < strtotime($subscription_expiry)) {

						$html[] = '<tr>
    						<th scope="row">' . $i . '</th>
	                        <td>' . $final_date . '</td>
	                        <td>' . $result->subscription_number . '</td>
	                        <td>' . $result->package . '</td>
	                        <td>' . $subh->subscription_expiry . '</td>
	                        <td>INR ' . $result->offered_price . '</td>
	                        <td>' . $subh->remaining_ads . '</td>
	                        ' . $joinText . '
	                        <td> ' . $subh->transaction_id . '</td>
	                        <td> ' . $subh->payment_status . '</td>
	                        <td> Active</td>
	                    </tr>';
					}




				}
			}
		} else if ($id == 'expiry') {
			$history = DB::table('subscription_orders')->where('user_id', $user_id)->where('delete_status', 0)->orderby('id', 'desc')->get();

			if (isset($history)) {
				foreach ($history as $index => $subh) {
					$i = $index + 1;
					$result = DB::table('subscriptions')->where('id', $subh->subscription_id)->first();
					$status_dates = date("d-m-Y");
					$no = explode(" ", $result->package_validity);
					$nos = $no[0] + 1;


					$dates = $subh->created_at;
					$date = date_create($dates);
					date_add($date, date_interval_create_from_date_string($nos . "days"));
					$subscription_expiry = date_format($date, "d-m-Y");
					$subs_date = explode(" ", $subh->created_at);
					$dats_subs = date_create($subs_date[0]);
					$time_subs = date_create($subs_date[1]);

					$cal_date = date_format($dats_subs, "d-m-Y");

					$final_date = date_format($dats_subs, "d-m-Y") . '|' . date_format($time_subs, "H:i A");

					if (strtotime($status_dates) > strtotime($subscription_expiry)) {
						$html[] = '<tr>
    						<th scope="row">' . $i . '</th>
	                        <td>' . $final_date . '</td>
	                         <td>' . $result->subscription_number . '</td>
	                        <td>' . $result->package . '</td>
	                        <td>' . $subh->subscription_expiry . '</td>
	                        <td>INR ' . $result->offered_price . '</td>
	                         <td>' . $subh->remaining_ads . '</td>
	                        ' . $joinText . '
	                        <td> ' . $subh->transaction_id . '</td>
	                        <td> ' . $subh->payment_status . '</td>
	                        <td> Expire</td>
	                        <td></td>
	                    </tr>';
					}

				}
			}
		}

		return response()->json($html);

	}

	public function my_subscription(Request $request)
	{
		$cust = Customer::find(session()->has('id'));
		if (session()->has('id') && !empty($cust)) {
			$user_id = session('id');
			$data['PremiumCategories'] = Categories::where('delete_status', '0')->where('premium', '1')->orderby('id', 'desc')->get();
			$data['TopCategories'] = Categories::where('delete_status', '0')->where('top', '1')->orderby('id', 'desc')->get();
			$data['TrendingCategories'] = Categories::where('delete_status', '0')->where('trending', '1')->orderby('id', 'desc')->get();
			$data['allcategories'] = Categories::where('delete_status', '0')->orderby('name', 'ASC')->get();
			$data['Pages'] = Pages::where('delete_status', '0')->get();
			$data['city'] = City::where('delete_status', '0')->orderby('name', 'asc')->get();
			$data['countries'] = Countries::where('delete_status', '0')->orderby('id', 'desc')->get();
			$data['states'] = States::where('delete_status', '0')->orderby('id', 'desc')->get();
			$data['zip'] = Zip::where('delete_status', '0')->orderby('id', 'desc')->get();
			$userIp = $request->ip();
			$data['locationinfo'] = \Location::get($userIp);
			$data['page'] = 'My Subscription';
			$data['city'] = City::where('delete_status', '0')->orderby('name', 'asc')->get();
			$data['my_ads'] = DB::table('ads_postings')->where('user_id', $user_id)->where('delete_status', 0)->orderby('id', 'desc')->get();
			$data['setting'] = Adminsettings::first();
			$data['remaining_ads'] = DB::table('subscription_orders')->where('user_id', $user_id)->where('delete_status', 0)->whereDate('subscription_expiry', '>', date('Y-m-d'))->sum('remaining_ads');
			$data['history'] = DB::table('subscription_history')->where('user_id', $user_id)->where('delete_status', 0)->orderby('id', 'desc')->get();
			$data['expiry_history'] = DB::table('subscription_orders')->where('user_id', $user_id)->where('delete_status', 0)->orderby('created_at', 'desc')->first();
			$data['subscription_exists'] = DB::table('subscription_history')->where('user_id', $user_id)->whereDate('subscription_expiry', '<', date('Y-m-d'))->where('delete_status', 0)->exists();
			$data['used_ads'] = $data['my_ads']->where('active_status', 1)->sum('active_status');
			$data['isAutoJoin'] = SubscriptionHistory::where('user_id', session('id'))
				->where('status', '0')
				->where('auto_join', '1')
				->where('subscription_expiry', '>', date("d-m-Y"))
				->where('delete_status', '0')->exists();

			//echo "<pre/>"; print_r($data['expiry_history']); die('djkfbvjkdbfv');

			return view('website.my-subscription', $data);
		} else {
			return redirect()->route('login');
		}
	}

	public function check_user_subscription(Request $request)
	{
		$user_id = $request->user_id;
		$category_subscription_result = DB::table('subscription_orders')->where('user_id', $user_id)->where('delete_status', 0)->orderby('id', 'desc')->get();


		$payment_status = $category_subscription_result[0]->payment_status;
		$used_ads = $category_subscription_result[0]->used_ads;
		$remaining_ads = $category_subscription_result[0]->remaining_ads;
		$status = $category_subscription_result[0]->status;


		$costing = DB::table('subscription_costing')->first();

		if ($payment_status != 'Completed') {
			return response()->json([
				'success' => '400',
				'msgText' => 'Get failure',
				'html' => 'Payment Is Pending'
			]);
		}

		if ($used_ads == $remaining_ads) {
			return response()->json([
				"success" => '500',
				"html" => view('website.ajax.subscription')->with([
					'category_id' => $category_subscription_result[0]->category_id,
					'id' => $category_subscription_result[0]->id,
					'costing' => $costing->ad_costing,
				])->render(),

			]);

		}

		if ($status == '1') {
			return response()->json([
				'success' => '402',
				'msgText' => 'Get failure',
				'html' => 'Subscription Validity Expired.'
			]);
		}

		return response()->json([
			'success' => '401',
			'msgText' => 'Get failure',
			'html' => 'Ads are Pending'
		]);


	}

	public function get_ads_buy(Request $request)
	{
		$data['price'] = $request->price;
		$data['id'] = $request->subscription_id;
		$data['category_id'] = $request->category_id;
		$data['no_ads'] = $request->ads;
		$data['Pages'] = Pages::where('delete_status', '0')->get();
		$data['city'] = City::where('delete_status', '0')->orderby('name', 'asc')->get();
		$data['countries'] = Countries::where('delete_status', '0')->orderby('id', 'desc')->get();
		$data['states'] = States::where('delete_status', '0')->orderby('id', 'desc')->get();
		$data['zip'] = Zip::where('delete_status', '0')->orderby('id', 'desc')->get();
		$data['PremiumCategories'] = Categories::where('delete_status', '0')->where('premium', '1')->orderby('id', 'desc')->get();
		$data['TopCategories'] = Categories::where('delete_status', '0')->where('top', '1')->orderby('id', 'desc')->get();
		$data['TrendingCategories'] = Categories::where('delete_status', '0')->where('trending', '1')->orderby('id', 'desc')->get();
		$data['allcategories'] = Categories::where('delete_status', '0')->orderby('name', 'ASC')->get();
		$userIp = $request->ip();
		$data['locationinfo'] = \Location::get($userIp);
		$data['page'] = 'Purchase Ads';

		return view('website.ads_payment', $data);
	}

	public function updateFcmToken(Request $request)
	{

		$request->validate([
			'id' => 'required',
			'fcm_token' => 'required|string',
		]);


		if ($request->id != '') {
			$user = Customer::findOrFail($request->id);

			$user->fcm_token = $request->fcm_token;
			if ($user->save()) {
				return response()->json(['success' => true], 200);
			} else {
				return response()->json(['success' => false], 500);
			}
		} else {
			return response()->json(['success' => false], 400);
		}



	}

	public function get_subscription_data($id)
	{
		$user_id = session('id');
		$history = DB::table('subscription_history')->where('user_id', $user_id)->where('delete_status', 0)->orderby('id', 'desc')->get();
		$logo_path = public_path('invoice/logo.svg');
		$logo_content = file_get_contents($logo_path, false);
		$logo_64 = 'data:image/svg;base64,' . base64_encode($logo_content);
		$gstsetting = Adminsettings::first();
		$user_detail = Customer::find($user_id);
		$subscriptionOrder = SubscriptionOrder::where('transaction_id', $id)->first();

		$subscription = Subscription::findOrFail($subscriptionOrder->subscription_id);
		$category = Categories::whereIn('id', explode(",", $subscriptionOrder->category_id))->pluck('name');

		$data = array(
			'history' => $history,
			'gstsetting' => $gstsetting,
			'user_detail' => $user_detail,
			'logo_64' => $logo_64,
			'subscriptionOrder' => $subscriptionOrder,
			'subscription' => $subscription,
			'category' => $category
		);

		$pdf = PDF::loadView('website.invoice.subscription', $data);
		return $pdf->download($subscription->package . '.pdf');

	}

	public function help(Request $request)
	{
		$cust = Customer::find(session()->has('id'));
		if (session()->has('id') && !empty($cust)) {
			$user_id = session('id');
			$data['PremiumCategories'] = Categories::where('delete_status', '0')->where('premium', '1')->orderby('id', 'desc')->get();
			$data['TopCategories'] = Categories::where('delete_status', '0')->where('top', '1')->orderby('id', 'desc')->get();
			$data['TrendingCategories'] = Categories::where('delete_status', '0')->where('trending', '1')->orderby('id', 'desc')->get();
			$data['allcategories'] = Categories::where('delete_status', '0')->orderby('name', 'ASC')->get();
			$data['Pages'] = Pages::where('delete_status', '0')->get();
			$data['city'] = City::where('delete_status', '0')->orderby('name', 'asc')->get();
			$data['countries'] = Countries::where('delete_status', '0')->orderby('id', 'desc')->get();
			$data['states'] = States::where('delete_status', '0')->orderby('id', 'desc')->get();
			$data['zip'] = Zip::where('delete_status', '0')->orderby('id', 'desc')->get();
			$userIp = $request->ip();
			$data['locationinfo'] = \Location::get($userIp);
			$data['page'] = 'Help and Support';
			$data['city'] = City::where('delete_status', '0')->orderby('name', 'asc')->get();
			$data['my_ads'] = DB::table('ads_postings')->where('user_id', $user_id)->where('delete_status', 0)->orderby('id', 'desc')->get();
			$data['faqCategories'] = Faqcategory::where('delete_status', '0')
				->whereHas('faqs', function ($query) {
					$query->where('delete_status', '0');
				})
				->with([
					'faqs' => function ($query) {
						$query->where('delete_status', '0');
					}
				])
				->orderBy('id', 'desc')
				->get();
			return view('website.help', $data);
		} else {
			return redirect()->route('login');
		}
	}

	public function ticketIndex(Request $request)
	{
		$user_id = session('id');
		$data['PremiumCategories'] = Categories::where('delete_status', '0')->where('premium', '1')->orderby('id', 'desc')->get();
		$data['TopCategories'] = Categories::where('delete_status', '0')->where('top', '1')->orderby('id', 'desc')->get();
		$data['TrendingCategories'] = Categories::where('delete_status', '0')->where('trending', '1')->orderby('id', 'desc')->get();
		$data['allcategories'] = Categories::where('delete_status', '0')->orderby('name', 'ASC')->get();
		$data['Pages'] = Pages::where('delete_status', '0')->get();
		$data['city'] = City::where('delete_status', '0')->orderby('name', 'asc')->get();
		$data['countries'] = Countries::where('delete_status', '0')->orderby('id', 'desc')->get();
		$data['states'] = States::where('delete_status', '0')->orderby('id', 'desc')->get();
		$data['zip'] = Zip::where('delete_status', '0')->orderby('id', 'desc')->get();
		$userIp = $request->ip();
		$data['locationinfo'] = \Location::get($userIp);
		$data['page'] = 'Tickets';
		$data['city'] = City::where('delete_status', '0')->orderby('name', 'asc')->get();
		$data['tickets'] = RaiseTicket::where('user_id', $user_id)->get();
		return view('website.tickets', $data);
	}

	public function call_back(Request $request)
	{
		if (session()->has('id')) {
			$user_id = session('id');
			$data['page'] = 'Get a Call Back';
			$data['city'] = City::where('delete_status', '0')->get();
			return view('website.call-back', $data);

		} else {
			return redirect()->route('login');
		}
	}

	public function post_call_back(Request $request)
	{
		$name = $request->name;
		$email = $request->email;
		$mobile = $request->mobile;

		CallBack::create([
			'name' => $name,
			'email' => $email,
			'mobile' => $mobile
		]);
		return redirect()->route('help');
	}

	public function raise_ticket(Request $request)
	{
		if (session()->has('id')) {
			$user_id = session('id');
			$data['page'] = 'Raise a Ticket';
			$data['subject'] = Subject::where('delete_status', '0')->get();
			$data['city'] = City::where('delete_status', '0')->orderby('name', 'asc')->get();
			return view('website.raise-ticket', $data);
		} else {
			return redirect()->route('login');
		}
	}

	public function post_raise_ticket(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'subject' => 'required|max:2000|min:0',
			'subject_query' => 'required|max:2000|min:0',
			'file' => 'mimes:jpg,jpeg,png,svg|max:2048',
		]);

		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator)->withInput();
		}
		$user_id = session('id');
		$user = Customer::findOrFail($user_id);
		$contact = Contact::orderby('id', 'desc')->first();

		$ticket = new RaiseTicket;
		if ($request->file('file')) {
			$imageName = time() . '.' . $request->file->extension();
			$request->file->move(public_path('uploads/subject'), $imageName);
			$ticket_image = url('public/uploads/subject') . '/' . $imageName;

		} else {

			$ticket_image = url('public/uploads/ads/dummy.jpeg');
		}

		$ticket->user_id = $user_id;
		$ticket->subject = $request->subject;
		$ticket->subject_query = $request->subject_query;
		$ticket->image = $ticket_image;
		if ($ticket->save()) {
			$ticketMail = Mail::to($contact->email)->send(new TicketMail($user->name, $request->subject, $request->subject_query, $ticket_image ?? null));
		}
		return redirect()->route('help');
	}
	public function user_chat(Request $request)
	{
		/*DB::table('chat_messages')->insert(array('user_id'=>'1','consumer_id'=>'3','reciever_id'=>'1','sender_id'=>'3','topic'=>'ok sorry i cant afford that much'));	*/

		$cust = Customer::find(session()->has('id'));
		if (session()->has('id') && !empty($cust)) {
			$user_id = session('id');

			$data['PremiumCategories'] = Categories::where('delete_status', '0')->where('premium', '1')->orderby('id', 'desc')->get();
			$data['TopCategories'] = Categories::where('delete_status', '0')->where('top', '1')->orderby('id', 'desc')->get();
			$data['TrendingCategories'] = Categories::where('delete_status', '0')->where('trending', '1')->orderby('id', 'desc')->get();
			$data['allcategories'] = Categories::where('delete_status', '0')->orderby('name', 'ASC')->get();
			$data['Pages'] = Pages::where('delete_status', '0')->get();
			$data['city'] = City::where('delete_status', '0')->orderby('name', 'asc')->get();
			$data['countries'] = Countries::where('delete_status', '0')->orderby('id', 'desc')->get();
			$data['states'] = States::where('delete_status', '0')->orderby('id', 'desc')->get();
			$data['zip'] = Zip::where('delete_status', '0')->orderby('id', 'desc')->get();
			$data['chatroom'] = DB::table('chat_room')->where('sender_id', $user_id)->orWhere('user_id', $user_id)->orderBy('id', 'desc')->get();
			$data['max_id'] = DB::table('chat_room')->where('sender_id', $user_id)->orWhere('user_id', $user_id)->max('id');
			$data['user_id'] = $user_id;

			if (count($data['chatroom']) == 0) {
				$data['chatroom'] = DB::table('chat_room')->where('user_id', $user_id)->where('isAdmin', 0)->orderBy('id', 'desc')->get();
			}

			if (!isset($data['max_id'])) {
				$data['max_id'] = DB::table('chat_room')->where('user_id', $user_id)->where('isAdmin', 0)->max('id');
			}

			$userIp = $request->ip();
			$data['locationinfo'] = \Location::get($userIp);
			$data['page'] = 'Chat';

			$data['isAdmin'] = 0;

			$data['customerinfo'] = Customer::find($user_id);
			$adType = 'Paid';
			$data['myads'] = Adposting::where('delete_status', '0')->where('user_id', $user_id)->orderByRaw("IF(ad_type = '{$adType}', ad_type, id) DESC")->get();
			$data['myadsenquiry'] = Adsenquiry::where('user_id', $user_id)->orderby('id', 'desc')->get();

			return view('website.chat', $data);

		} else {
			return redirect()->route('login');
		}
	}

	public function chat_read(Request $request)
	{
		$chat_id = $request->chat_id;
		$user_id = session('id');
		$allmessage = DB::table('chat_messages')->where('consumer_id', $user_id)->where('user_id', $chat_id)->where('is_read', '0')->get();
		if (isset($allmessage)) {
			foreach ($allmessage as $orderDetail) {
				DB::table('chat_messages')->where('consumer_id', $user_id)->where('user_id', $chat_id)->update(array('is_read' => '1'));
			}

			return response()->json([
				'success' => true,
				'msgText' => 'Created',
			]);

		}

	}

	public function clear_chat(Request $request)
	{
		$sender_id = $request->sender_id;
		$user_id = session('id');

		$chat_room = DB::table('chat_room')
			->where('user_id', $user_id)
			->where('sender_id', $sender_id)
			->where('clear_chat', '0')
			->first();

		if ($chat_room) {
			DB::table('chat_room')
				->where('user_id', $user_id)
				->where('sender_id', $sender_id)
				->update(['clear_chat' => '1']);

			DB::table('chat_messages')
				->where('user_id', $user_id)
				->where('consumer_id', $sender_id)
				->delete();

			return response()->json([
				'success' => true,
				'message' => 'Chat cleared successfully.',
			]);
		} else {
			return response()->json([
				'success' => false,
				'message' => 'No active chat found to clear.',
			]);
		}
	}

	public function block_chat(Request $request)
	{
		$sender_id = $request->sender_id;
		$enquiry_id = $request->enquiry_id;
		$reason = $request->block_reason;
		$enquiry = Adsenquiry::findOrFail($enquiry_id);

		if ($enquiry) {
			try {
				$isBlocked = $enquiry->isBlocked;

				if ($isBlocked) {
					// Decrement count if unblocked
					$enquiry->isBlocked = 0;
					$enquiry->save();

					DB::table('block_count')
						->where('user_id', $sender_id)
						->where('count', '>', 0)
						->decrement('count');

					$message = 'Unblocked';
				} else {
					// Increment count if blocked
					$enquiry->isBlocked = 1;
					$enquiry->block_reason = $reason;
					$enquiry->save();

					DB::table('block_count')
						->updateOrInsert(
							['user_id' => $sender_id],
							['count' => DB::raw('count + 1')]
						);

					$message = 'Blocked';

					// Send block notification email
					$user = Customer::findOrFail($sender_id);
					Mail::to($user->email)->send(new BlockMail('Your account has been blocked', $reason));

					// Check if user needs account termination notification
					$blockCount = DB::table('block_count')->where('user_id', $sender_id)->value('count');
					if ($blockCount >= 5) {
						Mail::to($user->email)->send(new AccountTermination());
					}
					if (!empty($user)) {
						$event = DefaultNotification::where('event', 'id_blocked')->first();
						if (!empty($event)) {
							$title = $event->title;
							$content = $event->content;
							$body = str_replace("#id", $user->member_id, $content);
							$notifyArray = array(
								'user_id' => $user->id,
								'event_name' => $event->event,
								'title' => $title,
								'body' => $body,
							);

							$this->singleUserNotification($notifyArray);
						}
					}

				}

				return response()->json([
					'success' => true,
					'message' => $message,
				]);
			} catch (\Exception $e) {
				return response()->json([
					'success' => false,
					'message' => 'Error occurred: ' . $e->getMessage(),
				]);
			}
		} else {
			return response()->json([
				'success' => false,
				'message' => 'No active chat found to block.',
			]);
		}
	}

	public function admin_chat(Request $request)
	{
		$ad_title = 'Chat Support';
		$userid = session('id');
		$consumer_id = 1;

		$useridexist = DB::table('chat_room')->where('user_id', $userid)->where('sender_id', $consumer_id)->exists();
		if ($useridexist) {
			//DB::table(chat_room)->where('user_id',$userid)->where('sender_id',$consumer_id)->delete();

		} else {

			DB::table('chat_room')->insert(array('user_id' => $userid, 'sender_id' => $consumer_id, 'message' => $ad_title, 'isAdmin' => 1));
		}

		$cust = Customer::find(session()->has('id'));
		if (session()->has('id') && !empty($cust)) {
			$user_id = session('id');
			$data['PremiumCategories'] = Categories::where('delete_status', '0')->where('premium', '1')->orderby('id', 'desc')->get();
			$data['TopCategories'] = Categories::where('delete_status', '0')->where('top', '1')->orderby('id', 'desc')->get();
			$data['TrendingCategories'] = Categories::where('delete_status', '0')->where('trending', '1')->orderby('id', 'desc')->get();
			$data['allcategories'] = Categories::where('delete_status', '0')->orderby('name', 'ASC')->get();
			$data['Pages'] = Pages::where('delete_status', '0')->get();
			$data['city'] = City::where('delete_status', '0')->orderby('name', 'asc')->get();
			$data['countries'] = Countries::where('delete_status', '0')->orderby('id', 'desc')->get();
			$data['states'] = States::where('delete_status', '0')->orderby('id', 'desc')->get();
			$data['zip'] = Zip::where('delete_status', '0')->orderby('id', 'desc')->get();
			$data['chatroom'] = DB::table('chat_room')->where('user_id', $user_id)->where("sender_id", 1)->where('isAdmin', '1')->orderBy('id', 'desc')->get();

			$data['max_id'] = DB::table('chat_room')->where('user_id', $user_id)->where("sender_id", 1)->where('isAdmin', '1')->max('id');
			$userIp = $request->ip();
			$data['locationinfo'] = \Location::get($userIp);
			$data['chatSup'] = "Chat Support";
			$data['page'] = 'Chat Support';
			$data['customerinfo'] = Customer::find($user_id);
			$data['user_id'] = $user_id;
			$data['isAdmin'] = 1;
			$adType = 'Paid';
			$data['myads'] = Adposting::where('delete_status', '0')->where('user_id', $user_id)->orderByRaw("IF(ad_type = '{$adType}', ad_type, id) DESC")->get();
			$data['myadsenquiry'] = Adsenquiry::where('user_id', $user_id)->orderby('id', 'desc')->get();

			return view('website.chat-owner', $data);

		} else {
			return redirect()->route('login');
		}
	}

	public function chat_with_owner(Request $request, $id, $userid)
	{
		$result = DB::table('ads_postings')->where('id', $id)->get();
		$ad_title = $result[0]->ad_title;
		$consumer_id = $result[0]->user_id;
		if ($userid) {
		}

		$useridexist = DB::table('chat_room')->where('user_id', $userid)->where('sender_id', $consumer_id)->exists();
		if ($useridexist) {
			//DB::table(chat_room)->where('user_id',$userid)->where('sender_id',$consumer_id)->delete();

		} else {

			DB::table('chat_room')->insert(array('user_id' => $userid, 'sender_id' => $consumer_id, 'message' => $ad_title));
		}

		$cust = Customer::find(session()->has('id'));
		if (session()->has('id') && !empty($cust)) {
			$user_id = session('id');
			$data['PremiumCategories'] = Categories::where('delete_status', '0')->where('premium', '1')->orderby('id', 'desc')->get();
			$data['TopCategories'] = Categories::where('delete_status', '0')->where('top', '1')->orderby('id', 'desc')->get();
			$data['TrendingCategories'] = Categories::where('delete_status', '0')->where('trending', '1')->orderby('id', 'desc')->get();
			$data['allcategories'] = Categories::where('delete_status', '0')->orderby('name', 'ASC')->get();
			$data['Pages'] = Pages::where('delete_status', '0')->get();
			$data['city'] = City::where('delete_status', '0')->orderby('name', 'asc')->get();
			$data['countries'] = Countries::where('delete_status', '0')->orderby('id', 'desc')->get();
			$data['states'] = States::where('delete_status', '0')->orderby('id', 'desc')->get();
			$data['zip'] = Zip::where('delete_status', '0')->orderby('id', 'desc')->get();
			$data['chatroom'] = DB::table('chat_room')->where('user_id', $user_id)->where('sender_id', $consumer_id)->where('isAdmin', 0)->orderBy('id', 'desc')->get();
			$data['max_id'] = DB::table('chat_room')->where('user_id', $user_id)->where('sender_id', $consumer_id)->where('isAdmin', 0)->max('id');

			$userIp = $request->ip();
			$data['locationinfo'] = \Location::get($userIp);
			$data['page'] = 'Home';
			$data['isAdmin'] = 0;
			$data['user_id'] = $user_id;
			$data['customerinfo'] = Customer::find($user_id);
			$data['myads'] = Adposting::where('delete_status', '0')->where('user_id', $user_id)->orderby('id', 'desc')->get();
			$data['myadsenquiry'] = Adsenquiry::where('user_id', $user_id)->orderby('id', 'desc')->get();

			return view('website.chat-owner', $data);

		} else {
			return redirect()->route('login');
		}

	}

	public function post_ads(Request $request)
	{
		$cust = Customer::find(session()->has('id'));
		if (session()->has('id') && !empty($cust)) {
			$data['PremiumCategories'] = Categories::where('delete_status', '0')->where('premium', '1')->orderby('id', 'desc')->get();
			$data['TopCategories'] = Categories::where('delete_status', '0')->where('top', '1')->orderby('id', 'desc')->get();
			$data['TrendingCategories'] = Categories::where('delete_status', '0')->where('trending', '1')->orderby('id', 'desc')->get();
			$data['allcategories'] = Categories::where('delete_status', '0')->orderby('name', 'ASC')->get();
			$data['Pages'] = Pages::where('delete_status', '0')->get();
			$data['city'] = City::where('delete_status', '0')->orderby('name', 'asc')->get();
			$userIp = $request->ip();
			$data['locationinfo'] = \Location::get($userIp);
			$data['page'] = 'Post Ads';

			return view('website.post_ads', $data);

		} else {
			return redirect()->route('login');
		}
	}

	public function ads_enguiry(Request $request)
	{
		$cust = Customer::find(session()->has('id'));
		if (session()->has('id') && !empty($cust)) {

			$id = $request->post_id;
			$result = DB::table('ads_postings')->where('id', $id)->get();
			$user_id = session('id');
			$user = Customer::with('blockUser')->findOrFail($user_id);
			$enquiries = Adsenquiry::where('user_id', $user_id)->where('post_id', $id)->first();
			if (isset($enquiries->isBlocked) && $enquiries->isBlocked == 1) {
				return redirect()->back()->with('error', "You have been blocked by this user");
			}
			Adsenquiry::create([
				'name' => $user->name,
				'email' => $user->email,
				'mobile' => $user->mobile ?? "",
				'post_id' => $request->post_id,
				'user_id' => $user_id,
				'receiver_id' => $result[0]->user_id,
				'status' => 'pending'
			]);
			$customerX = Customer::where('id', $result[0]->user_id)->first();

			if ($customerX->fcm_token) {
				$title = 'Enquiry Received!';
				$body = $user->name . ' sent a enquiry.';
				$image = null;
				$response = $this->sendNotification($title, $body, $customerX->fcm_token, $image);
			}
			// 		$userprofile			= new Adsenquiry;
			// 		$userprofile->name 		=$request->name;
			// 		$userprofile->email 	=$request->email;
			// 		$userprofile->mobile  	= $request->mobile;
			// 		$userprofile->message 	= $request->message;
			// 		$userprofile->post_id 	= $request->post_id;
			// 		$userprofile->user_id 	= $user_id;
			//		$userprofile->save();

			\Session::put('success', 'Enquiry Posted Successfully.');
			return redirect("ads-details/$id");
		} else {
			return redirect()->route('login');
		}
	}

	public function replyEnquiry(Request $request)
	{
		$adsEnquiry = Adsenquiry::findOrFail($request->ad_id);
		$adsEnquiry->update([
			'reply' => $request->reply ?? "",
			'status' => 'approved'
		]);
		return redirect()->back()->with(['Enquiry updated successfully']);
	}

	public function ad_forms(Request $request, $formtype, $catid, $subcatid)
	{
		$user_id = session('id');

		$data['PremiumCategories'] = Categories::where('delete_status', '0')->where('premium', '1')->orderby('id', 'desc')->get();
		$data['TopCategories'] = Categories::where('delete_status', '0')->where('top', '1')->orderby('id', 'desc')->get();
		$data['TrendingCategories'] = Categories::where('delete_status', '0')->where('trending', '1')->orderby('id', 'desc')->get();
		$data['allcategories'] = Categories::where('delete_status', '0')->orderby('name', 'ASC')->get();
		$data['Pages'] = Pages::where('delete_status', '0')->get();
		$data['city'] = City::where('delete_status', '0')->orderby('name', 'asc')->get();
		$data['state'] = States::where('delete_status', '0')->orderby('name', 'asc')->get();
		$data['singlecategory'] = Categories::find($catid);
		$data['singlesubcatid'] = Subcategories::where('category_id', $catid)->where('id', $subcatid)->first();
		$userIp = $request->ip();

		$data['locationinfo'] = \Location::get($userIp);
		$data['categoryid'] = $catid;
		$data['subcatid'] = $subcatid;
		$data['userinfo'] = Customer::find($user_id);
		$data['jobs'] = Job::where('status', 0)->where('delete_status', 0)->get();
		$data['construction'] = Construction::where('status', 0)->where('delete_status', 0)->get();
		$data['furnishing'] = Furnishing::where('status', 0)->where('delete_status', 0)->get();
		$data['brand'] = Brand::where('type', 'Cars')->where('status', 0)->where('delete_status', 0)->get();
		$data['mobilebrand'] = Brand::where('type', 'Mobile')->where('status', 0)->where('delete_status', 0)->get();
		$data['vehicleType'] = Vehicletypes::where('status', 0)->where('delete_status', 0)->get();
		$data['FuelType'] = Fueltype::where('status', 0)->where('delete_status', 0)->get();
		$data['transmission'] = Transmission::where('status', 0)->where('delete_status', 0)->get();
		$data['residence'] = Residence::where('status', 0)->where('delete_status', 0)->get();
		$data['facing'] = Facing::where('status', 0)->where('delete_status', 0)->get();
		$data['page'] = 'AD Forms';
		$result_category_id = DB::table('subscriptions_free_trials')->where('category_id', $catid)->get();
		if (isset($result_category_id) && count($result_category_id) > 0) {
			$data['no_of_ads'] = $result_category_id[0]->no_of_ads;
		} else {
			$data['no_of_ads'] = 10;
		}
		$data['active_postings_count'] = DB::table('ads_postings')->where('user_id', $user_id)->where('ad_type', 'Free')->where('active_status', '1')->where('category_id', $catid)->count('id');
		$data['pending_ads_count'] = DB::table('ads_postings')
			->where('user_id', $user_id)
			->where('ad_type', 'Free')
			->where('active_status', '0')
			->where('category_id', $catid)
			->count('id');

		if ($formtype == 1) {
			$data['form_id'] = $formtype;
			return view('website.forms.jobsform', $data);

		} elseif ($formtype == 2) {
			$data['form_id'] = $formtype;
			return view('website.forms.mobileform', $data);

		} elseif ($formtype == 3) {
			$data['form_id'] = $formtype;
			return view('website.forms.vehicleform', $data);

		} elseif ($formtype == 4) {
			$data['form_id'] = $formtype;
			return view('website.forms.propertyform', $data);

		} else {
			$data['form_id'] = $formtype;


			return view('website.forms.commonform', $data);
		}
	}

	public function edit_ads(Request $request, $formtype, $catid, $id)
	{
		$user_id = session('id');
		$formtype = decrypt($formtype);
		$id = decrypt($id);
		$catid = decrypt($catid);
		$data['PremiumCategories'] = Categories::where('delete_status', '0')->where('premium', '1')->orderby('id', 'desc')->get();
		$data['TopCategories'] = Categories::where('delete_status', '0')->where('top', '1')->orderby('id', 'desc')->get();
		$data['TrendingCategories'] = Categories::where('delete_status', '0')->where('trending', '1')->orderby('id', 'desc')->get();
		$data['allcategories'] = Categories::where('delete_status', '0')->orderby('name', 'ASC')->get();
		$data['Pages'] = Pages::where('delete_status', '0')->get();
		$data['city'] = City::where('delete_status', '0')->orderby('name', 'asc')->get();
		$data['state'] = States::where('delete_status', '0')->get();
		$data['location'] = Location::where('delete_status', '0')->get();
		$data['singlecategory'] = Categories::find($catid);
		$data['singlesubcatid'] = Subcategories::where('category_id', $catid)->first();

		$userIp = $request->ip();

		$data['locationinfo'] = \Location::get($userIp);  //dd($data['locationinfo']);
		$data['categoryid'] = $catid;
		$data['subcatid'] = $data['singlesubcatid']->id;
		$data['userinfo'] = Customer::find($user_id);
		$data['jobs'] = Job::where('status', 0)->where('delete_status', 0)->get();
		$data['construction'] = Construction::where('status', 0)->where('delete_status', 0)->get();
		$data['furnishing'] = Furnishing::where('status', 0)->where('delete_status', 0)->get();
		$data['vehiclebrand'] = Brand::where('type', 'Cars')->where('status', 0)->where('delete_status', 0)->get();
		$data['mobilebrand'] = Brand::where('type', 'Mobile')->where('status', 0)->where('delete_status', 0)->get();
		$data['vehicleType'] = Vehicletypes::where('status', 0)->where('delete_status', 0)->get();
		$data['FuelType'] = Fueltype::where('status', 0)->where('delete_status', 0)->get();
		$data['transmission'] = Transmission::where('status', 0)->where('delete_status', 0)->get();
		$data['residence'] = Residence::where('status', 0)->where('delete_status', 0)->get();
		$data['facing'] = Facing::where('status', 0)->where('delete_status', 0)->get();

		$data['adposting'] = Adposting::find($id);
		$data['vehicle'] = Vehicleform::where('ads_id', $data['adposting']->ad_id)->first();
		$data['mobile'] = Mobileform::where('ads_id', $data['adposting']->ad_id)->first();
		$data['job'] = Jobforms::where('ads_id', $data['adposting']->ad_id)->first();
		$data['property'] = Propertyform::where('ads_id', $data['adposting']->ad_id)->first();
		$data['common'] = Commonform::where('ads_id', $data['adposting']->ad_id)->first();
		$data['postimage'] = AdPostingImage::where('ads_id', $data['adposting']->ad_id)->get();
		$data['page'] = 'AD Forms';

		if ($formtype == 1) {
			$data['form_id'] = $formtype;
			return view('website.ads.jobsform', $data);

		} elseif ($formtype == 2) {
			$data['form_id'] = $formtype;
			return view('website.ads.mobileform', $data);

		} elseif ($formtype == 3) {
			$data['form_id'] = $formtype;
			return view('website.ads.vehicleform', $data);

		} elseif ($formtype == 4) {
			$data['form_id'] = $formtype;
			return view('website.ads.propertyform', $data);

		} else {
			$data['form_id'] = $formtype;
			return view('website.ads.commonform', $data);
		}
	}
	public function view_ads($id)
	{
		try {
			$data = Adposting::with('subscriptionhistory', 'category', 'subcategory')->findOrFail($id);
			return response()->json([
				"success" => true,
				'data' => $data,
			]);
		} catch (\Exception $ex) {
			return response()->json([
				"success" => false,
				'msgText' => $ex->getMessage(),
			]);
		}
	}
	public function view_subscriptions($id)
	{
		try {
			$data = SubscriptionHistory::findOrFail($id);
			$categories = \App\Models\Categories::whereIn('id', explode(",", $data->category_id))->pluck('name');
			return response()->json([
				"success" => true,
				"html" => view('website.orders.show')->with([
					'data' => $data,
					'categories' => $categories
				])->render(),
			]);
		} catch (\Exception $ex) {
			return response()->json([
				"success" => false,
				'msgText' => $ex->getMessage(),
			]);
		}
	}

	public function view_auto_seeds_member($id)
	{
		try {
			$data = Customer_child::where('subscription_id', $id)->get();
			return response()->json([
				"success" => true,
				"html" => view('website.orders.show-auto-seeds-member')->with([
					'datas' => $data
				])->render(),
			]);
		} catch (\Exception $ex) {
			return response()->json([
				"success" => false,
				'msgText' => $ex->getMessage(),
			]);
		}
	}
	public function my_orders()
	{
		$user_id = session('id');
		$data['page'] = 'My Orders';
		$data['datas'] = SubscriptionHistory::where('user_id', $user_id)->get();
		return view('website.orders.index', $data);
	}
	public function my_team(Request $request)
	{
		$user_id = session('id');
		$data['PremiumCategories'] = Categories::where('delete_status', '0')->where('premium', '1')->orderby('id', 'desc')->get();
		$data['TopCategories'] = Categories::where('delete_status', '0')->where('top', '1')->orderby('id', 'desc')->get();
		$data['TrendingCategories'] = Categories::where('delete_status', '0')->where('trending', '1')->orderby('id', 'desc')->get();
		$data['allcategories'] = Categories::where('delete_status', '0')->orderby('name', 'ASC')->get();
		$data['allsubcategories'] = Subcategories::where('delete_status', '0')->get();
		$data['about'] = About::where('delete_status', '0')->orderby('id', 'desc')->get();
		$data['Pages'] = Pages::where('delete_status', '0')->get();
		$data['city'] = City::where('delete_status', '0')->orderby('name', 'asc')->get();
		$userIp = $request->ip();
		$data['locationinfo'] = \Location::get($userIp);
		$data['page'] = 'My Seeds';
		$data['datas'] = Customer_child::where('user_id', $user_id)->get();
		return view('website.my-team', $data);
	}

	public function post_job_form(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'fullname' => 'required|max:50|min:0',
			'email' => 'required|max:50|min:0',
			'salary_period' => 'required|max:50|min:0',
			'position_type' => 'required|max:50|min:0',
			'salary_from' => 'required|max:50|min:0',
			'salary_to' => 'required|max:50|min:0',
			'ad_title' => 'required|max:50|min:0',
			'description' => 'required|max:2500|min:0',
			'file' => 'required|image|max:8192',   // 8MB max size
			'file1' => 'nullable|image|max:8192',   // 8MB max size, nullable
			'file2' => 'nullable|image|max:8192',   // 8MB max size, nullable
			'file3' => 'nullable|image|max:8192',   // 8MB max size, nullable
			'file4' => 'nullable|image|max:8192',   // 8MB max size, nullable
		]);

		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator)->withInput();
		}
		$subscription = '0';
		$category_id = $request->category_id;
		$user_id = $request->user_id;

		$ads_postings_count = DB::table('ads_postings')->where('user_id', $user_id)->where('delete_status', 0)->count('id');

		//$category_subscription_exists = DB::table('subscription_orders')->where('category_id',$category_id)->where('user_id',$user_id)->exists();

		$category_subscription_exists = DB::table("subscription_history")
			->where('user_id', $user_id)
			->where('status', '0')
			->exists();
		$pending_ads_count = DB::table('ads_postings')
			->where('user_id', $user_id)
			->where('delete_status', 0)
			->where('active_status', '0')
			->count('id');

			
		$category_id_exists = DB::table('subscriptions_free_trials')->where('category_id', $category_id)->exists();
		if ($category_subscription_exists) {
			//$category_subscription_result = DB::table('subscription_orders')->where('category_id',$category_id)->where('user_id',$user_id)->get();

			$category_subscription_result = DB::table("subscription_history")
				->where('user_id', $user_id)
				->where('status', '0')
				->get();


			$payment_status = $category_subscription_result[0]->payment_status;
			$used_ads = $category_subscription_result[0]->used_ads;
			$remaining_ads = $category_subscription_result[0]->remaining_ads;
			$status = $category_subscription_result[0]->status;

			if ($payment_status != 'Completed') {

				\Session::put('success', 'Payment Is Pending');
				return redirect()->route('purchase-subscription');
			}

			$usedAdsTotal = 0;
			$remainingAdsTotal = 0;

			foreach ($category_subscription_result as $subscription) {
				$usedAdsTotal += $subscription->used_ads;
				$remainingAdsTotal += $subscription->remaining_ads;
			}
			if ($ads_postings_count >= $remainingAdsTotal) {
				if ($request->hasFile('file')) {
					$imageName = time() . '.' . $request->file->extension();
					$request->file->move(public_path('uploads/ads'), $imageName);
					$imagePath = public_path('uploads/ads/' . $imageName);
					ImageOptimizer::optimize($imagePath);
					$imageSession = url('public/uploads/ads') . '/' . $imageName;
				}

				if ($request->hasFile('file1')) {
					$file = $request->file('file1');
					$name = time() . rand(1, 100) . '.' . $file->extension();
					$file->move(public_path('uploads/ads'), $name);
					$imagePath = public_path('uploads/ads/' . $name);
					ImageOptimizer::optimize($imagePath);
					$imageSession2 = url('public/uploads/ads') . '/' . $name;
				}

				if ($request->hasFile('file2')) {
					$file2 = $request->file('file2');
					$name2 = time() . rand(1, 100) . '.' . $file2->extension();
					$file2->move(public_path('uploads/ads'), $name2);
					$imagePath = public_path('uploads/ads/' . $name2);
					ImageOptimizer::optimize($imagePath);
					$imageSession3 = url('public/uploads/ads') . '/' . $name2;
				}
				if ($request->hasFile('file3')) {
					$file3 = $request->file('file3');
					$name3 = time() . rand(1, 100) . '.' . $file3->extension();
					$file3->move(public_path('uploads/ads'), $name3);
					$imagePath = public_path('uploads/ads/' . $name3);
					ImageOptimizer::optimize($imagePath);
					$imageSession4 = url('public/uploads/ads') . '/' . $name3;
				}
				if ($request->hasFile('file4')) {
					$file4 = $request->file('file4');
					$name4 = time() . rand(1, 100) . '.' . $file4->extension();
					$file4->move(public_path('uploads/ads'), $name4);
					$imagePath = public_path('uploads/ads/' . $name4);
					ImageOptimizer::optimize($imagePath);
					$imageSession5 = url('public/uploads/ads') . '/' . $name4;
				}

				Session::put('UserProfile', [
					'image' => $imageSession,
					'ads_validity' => $ads_validity,
					'ad_id' => $rand = mt_rand(1500, 5000),
					'category_id' => $request->category_id,
					'user_id' => $request->user_id,
					'sub_category_id' => $request->subcatid,
					'formtype' => $request->formtype,
					'fullname' => $request->fullname,
					'email' => $request->email,
					'mobile' => $request->mobile,
					'location' => $request->ip(),
					'city' => $request->city,
					'price' => $request->price,
					'ad_title' => $request->ad_title,
					'ad_type' => 'Free',
					'active_status' => '0',
					'ad_view_count' => '0',
					'description' => $request->description,
					'delete_status' => '0',
					'status' => '0'
				]);
				Session::put('ImageAd', ['image1' => $imageSession ?? null, 'image2' => $imageSession2 ?? null, 'image3' => $imageSession3 ?? null, 'image4' => $imageSession4 ?? null,]);
				Session::put('CommonDetail', [
					'user_id' => $request->user_id,
					'category_id' => $request->category_id,
					'sub_category_id' => $request->subcatid,
					'formtype' => $request->formtype,
					'fullname' => $request->fullname,
					'email' => $request->email,
					'mobile' => $request->mobile,
					'location' => $request->ip(),
					'state' => $request->state,
					'state_name' => $request->state_name,
					'city' => $request->city,
					'city_name' => $request->city_name,
					'neibourhood' => $request->neibourhood,
					'price' => $request->price,
					'ad_title' => $request->ad_title,
					'ad_type' => 'Free',
					'description' => $request->description
					,
					'delete_status' => '0',
					'status' => '0'
				]);
				\Session::put('success', 'All Ads Are Used in the bucket, please empty the bucket or buy new subscription.');
				return redirect()->route('purchase-subscription');
			}

			if ($status == '1') {
				if ($request->hasFile('file')) {
					$imageName = time() . '.' . $request->file->extension();
					$request->file->move(public_path('uploads/ads'), $imageName);
					$imageSession = url('public/uploads/ads') . '/' . $imageName;
				}
				if ($request->hasFile('file1')) {
					$file = $request->file('file1');
					$name = time() . rand(1, 100) . '.' . $file->extension();
					$file->move(public_path('uploads/ads'), $name);
					$imageSession2 = url('public/uploads/ads') . '/' . $name;
				}

				if ($request->hasFile('file2')) {
					$file2 = $request->file('file2');
					$name2 = time() . rand(1, 100) . '.' . $file2->extension();
					$file2->move(public_path('uploads/ads'), $name2);
					$imageSession3 = url('public/uploads/ads') . '/' . $name2;
				}
				if ($request->hasFile('file3')) {
					$file3 = $request->file('file3');
					$name3 = time() . rand(1, 100) . '.' . $file3->extension();
					$file3->move(public_path('uploads/ads'), $name3);
					$imageSession4 = url('public/uploads/ads') . '/' . $name3;
				}
				if ($request->hasFile('file4')) {
					$file4 = $request->file('file4');
					$name4 = time() . rand(1, 100) . '.' . $file4->extension();
					$file4->move(public_path('uploads/ads'), $name4);
					$imageSession5 = url('public/uploads/ads') . '/' . $name4;
				}


				Session::put('UserProfile', [
					'image' => $imageSession,
					'ads_validity' => $ads_validity,
					'ad_id' => $rand = mt_rand(1500, 5000),
					'category_id' => $request->category_id,
					'user_id' => $request->user_id,
					'sub_category_id' => $request->subcatid,
					'formtype' => $request->formtype,
					'fullname' => $request->fullname,
					'email' => $request->email,
					'mobile' => $request->mobile,
					'location' => $request->ip(),
					'city' => $request->city,
					'salary_from' => $request->salary_from,
					'salary_to' => $request->salary_to,
					'ad_title' => $request->ad_title,
					'ad_type' => 'Free',
					'active_status' => '0',
					'ad_view_count' => '0',
					'description' => $request->description,
					'delete_status' => '0',
					'status' => '0'
				]);
				Session::put('ImageAd', ['image1' => $imageSession ?? null, 'image2' => $imageSession2 ?? null, 'image3' => $imageSession3 ?? null, 'image4' => $imageSession4 ?? null,]);
				Session::put('JobDetail', [
					'user_id' => $request->user_id,
					'category_id' => $request->category_id,
					'sub_category_id' => $request->subcatid,
					'formtype' => $request->formtype,
					'fullname' => $request->fullname,
					'email' => $request->email,
					'mobile' => $request->mobile,
					'location' => $request->ip(),
					'state' => $request->state,
					'state_name' => $request->state_name,
					'city' => $request->city,
					'city_name' => $request->city_name,
					'neibourhood' => $request->neibourhood,
					'salary_period' => $request->salary_period,
					'ad_title' => $request->ad_title,
					'ad_type' => 'Free',
					'description' => $request->description,
					'position_type' => $request->position_type,
					'salary_from' => $request->salary_from,
					'salary_to' => $request->salary_to,
					'delete_status' => '0',
					'status' => '0'
				]);


				\Session::put('success', 'Subscription Validity Expired');
				return redirect()->route('purchase-subscription');
			}
		}

		$no = $ads_validity;
		$dates = date("d-m-Y");
		$date = date_create($dates);
		date_add($date, date_interval_create_from_date_string($no . "days"));
		$subscription_expiry = date_format($date, "d-m-Y");
		$customerprofile = Customer::find($user_id);
		if ($request->file('changeprofile')) {
			$imageName = time() . '.' . $request->changeprofile->extension();
			$request->changeprofile->move(public_path('uploads/ads'), $imageName);
			$customerprofile->image = url('public/uploads/ads') . '/' . $imageName;

		} else {
			$customerprofile->image = url('public/uploads/ads/dummy.jpeg');
		}

		if ($request->file('userprofile')) {
			$imageName = time() . '.' . $request->userprofile->extension();
			$request->userprofile->move(public_path('uploads/ads'), $imageName);
			$customerprofile->image = url('public/uploads/ads') . '/' . $imageName;

		} else {
			$customerprofile->image = url('public/uploads/ads/dummy.jpeg');
		}

		$customerprofile->save();

		if (isset($request->is_mobile_hide)) {
			$hide_mobile = '1';
		} else {
			$hide_mobile = '0';
		}

		$userprofile = new Adposting;


		$userprofile->ad_id = $rand = mt_rand(1500, 5000);
		$userprofile->user_id = $request->user_id;
		$userprofile->subscription_id = $category_subscription_result[0]->id;
		$userprofile->user_id = $request->user_id;
		$userprofile->category_id = $request->category_id;
		$userprofile->sub_category_id = $request->subcatid;
		$userprofile->formtype = $request->formtype;
		$userprofile->fullname = $request->fullname;
		$userprofile->email = $request->email;
		$userprofile->mobile = $request->mobile;
		$userprofile->is_mobile_hide = $hide_mobile;
		$userprofile->location = $request->ip();
		$userprofile->salary_from = $request->salary_from;
		$userprofile->salary_to = $request->salary_to;
		$userprofile->ad_title = $request->ad_title;
		$userprofile->ad_type = 'Free';
		//$userprofile->ad_expiry     = $subscription_expiry;
		$userprofile->active_status = '0';
		$userprofile->ad_view_count = '0';
		$userprofile->ads_validity = $ads_validity;
		$userprofile->description = $request->description;
		$userprofile->city = $request->city;
		$userprofile->delete_status = '0';
		$userprofile->status = '0';
		//	$userprofile->save();

		$ads_id = $userprofile->ad_id;

		if ($request->hasFile('file')) {
			$imageName = time() . '.' . $request->file->extension();
			$request->file->move(public_path('uploads/ads'), $imageName);
			$imagePath = public_path('uploads/ads/' . $imageName);
			ImageOptimizer::optimize($imagePath);
			$userprofile->image = url('public/uploads/ads') . '/' . $imageName;
			AdPostingImage::create([
				'ads_id' => $ads_id,
				'image' => url('public/uploads/ads') . '/' . $imageName,
				'image_no' => '1'
			]);

		} else {
			$userprofile->image = url('public/uploads/ads/dummy.jpeg');
		}
		$userprofile->save();

		$adimage = $userprofile->image;

		if ($request->hasFile('file1')) {
			$file = $request->file('file1');
			$name = time() . rand(1, 100) . '.' . $file->extension();
			$file->move(public_path('uploads/ads'), $name);
			$imagePath = public_path('uploads/ads/' . $name);
			ImageOptimizer::optimize($imagePath);
			AdPostingImage::create([
				'ads_id' => $ads_id,
				'image' => url('public/uploads/ads') . '/' . $name,
				'image_no' => '2'
			]);
		}

		if ($request->hasFile('file2')) {
			$file2 = $request->file('file2');
			$name2 = time() . rand(1, 100) . '.' . $file2->extension();
			$file2->move(public_path('uploads/ads'), $name2);
			$imagePath = public_path('uploads/ads/' . $name2);
			ImageOptimizer::optimize($imagePath);
			AdPostingImage::create([
				'ads_id' => $ads_id,
				'image' => url('public/uploads/ads') . '/' . $name2,
				'image_no' => '3'
			]);
		}
		if ($request->hasFile('file3')) {
			$file3 = $request->file('file3');
			$name3 = time() . rand(1, 100) . '.' . $file3->extension();
			$file3->move(public_path('uploads/ads'), $name3);
			$imagePath = public_path('uploads/ads/' . $name3);
			ImageOptimizer::optimize($imagePath);
			AdPostingImage::create([
				'ads_id' => $ads_id,
				'image' => url('public/uploads/ads') . '/' . $name3,
				'image_no' => '4'
			]);
		}

		if ($request->hasFile('file4')) {
			$file4 = $request->file('file4');
			$name4 = time() . rand(1, 100) . '.' . $file4->extension();
			$file4->move(public_path('uploads/ads'), $name4);
			$imagePath = public_path('uploads/ads/' . $name4);
			ImageOptimizer::optimize($imagePath);
			AdPostingImage::create([
				'ads_id' => $ads_id,
				'image' => url('public/uploads/ads') . '/' . $name4,
				'image_no' => '5'
			]);
		}

		$userprofile = new Jobforms;

		$userprofile->image = $adimage;
		$userprofile->ads_id = $ads_id;
		$userprofile->user_id = $request->user_id;
		$userprofile->category_id = $request->category_id;
		$userprofile->sub_category_id = $request->subcatid;
		$userprofile->formtype = $request->formtype;
		$userprofile->fullname = $request->fullname;
		$userprofile->email = $request->email;
		$userprofile->mobile = $request->mobile;
		$userprofile->location = $request->ip();
		$userprofile->state = $request->state;
		$userprofile->state_name = $request->state_name;
		$userprofile->city = $request->city;
		$userprofile->city_name = $request->city_name;
		$userprofile->neibourhood = $request->neibourhood;
		$userprofile->salary_period = $request->salary_period;
		$userprofile->position_type = $request->position_type;
		$userprofile->salary_from = $request->salary_from;
		$userprofile->salary_to = $request->salary_to;
		$userprofile->ad_title = $request->ad_title;
		$userprofile->ad_type = 'Free';
		$userprofile->description = $request->description;
		$userprofile->delete_status = '0';
		$userprofile->status = '0';
		$userprofile->save();

		// 		if($category_subscription_exists)
// 		{
// 			//$category_subscription_result = DB::table('subscription_orders')->where('category_id',$category_id)->where('user_id',$user_id)->get();
// 			$category_subscription_result = 	DB::table("subscription_history")
//             ->select("subscription_history.*")
//             ->whereRaw("find_in_set('".$category_id."',subscription_history.category_id)")
//             ->where('user_id',$user_id)
//             ->where('status','0')
//             ->get();

		// 			$used_ads 	= $category_subscription_result[0]->used_ads;
// 			$total_ads 	= $used_ads + 1;

		// 			DB::table("subscription_history")
//             ->select("subscription_history.*")
//             ->whereRaw("find_in_set('".$category_id."',subscription_history.category_id)")
//             ->where('user_id',$user_id)
//             ->where('status','0')
//             ->update(array('used_ads'=>$total_ads));

		// 			//DB::table('subscription_orders')->where('category_id',$category_id)->where('user_id',$user_id)->update(array('used_ads'=>$total_ads));
// 		}
// 		premium::create([
// 				'user_id' => $user_id,
// 				'subcription_id' => '',
// 				'ad_view_count_id' => '',

		// 				]);
		\Session::put('success', 'Post Added Successfully.');
		//return redirect("user-dashboard");
		return redirect("thank-you");
	}

	public function edit_post_job_form(Request $request)
	{

		$validator = Validator::make($request->all(), [
			'fullname' => 'required|max:50|min:0',
			'email' => 'required|max:50|min:0',
			//	'mobile'        => 'required|max:50|min:0',
			//'location'    => 'required|max:50|min:0',
			'salary_period' => 'required|max:50|min:0',
			'position_type' => 'required|max:50|min:0',
			'salary_from' => 'required|max:50|min:0',
			'salary_to' => 'required|max:50|min:0',
			'ad_title' => 'required|max:50|min:0',
			'description' => 'required|max:2500|min:0',
		]);

		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator)->withInput();
		}
		$subscription = '0';
		$category_id = $request->category_id;
		$user_id = $request->user_id;

		$ads_postings_count = DB::table('ads_postings')->where('user_id', $user_id)->where('delete_status', 0)->count('id');

		//$category_subscription_exists = DB::table('subscription_orders')->where('category_id',$category_id)->where('user_id',$user_id)->exists();

		$category_subscription_exists = DB::table("subscription_history")
			->where('user_id', $user_id)
			->where('status', '0')
			->exists();
		//dd($category_subscription_exists);

		$category_id_exists = DB::table('subscriptions_free_trials')->where('category_id', $category_id)->exists();
		if ($category_id_exists) {
			$result_category_id = DB::table('subscriptions_free_trials')->where('category_id', $category_id)->get();

			$no_of_ads = $result_category_id[0]->no_of_ads;
			$ads_validity = $result_category_id[0]->ads_validity;

			// 			if($no_of_ads == $ads_postings_count  && $category_subscription_exists != 1)
// 			{
// 				\Session::put('success','No More Free Ads Purchase Subscription For More Posting.');
// 				return redirect()->route('purchase-subscription');
// 			}
		}
		if ($category_subscription_exists) {
			//$category_subscription_result = DB::table('subscription_orders')->where('category_id',$category_id)->where('user_id',$user_id)->get();

			$category_subscription_result = DB::table("subscription_history")
				->where('user_id', $user_id)
				->where('status', '0')
				->get();

			$payment_status = $category_subscription_result[0]->payment_status;
			$used_ads = $category_subscription_result[0]->used_ads;
			$remaining_ads = $category_subscription_result[0]->remaining_ads;
			$status = $category_subscription_result[0]->status;

			// 			if($payment_status != 'Completed')
// 			{
// 				\Session::put('success','Payment Is Pending');
// 				return redirect()->route('purchase-subscription');
// 			}

			// 			if($used_ads == $remaining_ads)
// 			{
// 				\Session::put('success','All Ads Are Used.');
// 				return redirect()->route('purchase-subscription');
// 			}
			if ($status == '1') {
				\Session::put('success', 'Subscription Validity Expired');
				return redirect()->route('purchase-subscription');
			}
		}

		$no = $ads_validity;
		$dates = date("d-m-Y");
		$date = date_create($dates);
		date_add($date, date_interval_create_from_date_string($no . "days"));
		$subscription_expiry = date_format($date, "d-m-Y");

		$customerprofile = Customer::find($user_id);

		if ($request->file('changeprofile')) {
			$imageName = time() . '.' . $request->changeprofile->extension();
			$request->changeprofile->move(public_path('uploads/ads'), $imageName);
			$customerprofile->image = url('public/uploads/ads') . '/' . $imageName;

		} else {
			$customerprofile->image = url('public/uploads/ads/dummy.jpeg');
		}

		if ($request->file('userprofile')) {
			$imageName = time() . '.' . $request->userprofile->extension();
			$request->userprofile->move(public_path('uploads/ads'), $imageName);
			$customerprofile->image = url('public/uploads/ads') . '/' . $imageName;

		} else {
			$customerprofile->image = url('public/uploads/ads/dummy.jpeg');
		}

		$customerprofile->save();

		if (isset($request->is_mobile_hide)) {
			$hide_mobile = '1';
		} else {
			$hide_mobile = '0';
		}

		$userprofile = Adposting::find($request->ad_id);

		if (isset($request->mobile)) {
			$mobile_number = $request->mobile;
		} else {
			$mobile_number = $userprofile->mobile;
		}
		$currenAdStatus = $userprofile->status;
		$userprofile->ad_id = $request->ads_id;
		$userprofile->user_id = $request->user_id;
		$userprofile->category_id = $request->category_id;
		$userprofile->sub_category_id = $request->subcatid;
		$userprofile->formtype = $request->formtype;
		$userprofile->fullname = $request->fullname;
		$userprofile->email = $request->email;
		//	$userprofile->mobile          = $request->mobile;
		$userprofile->mobile = $mobile_number;
		$userprofile->is_mobile_hide = $hide_mobile;
		$userprofile->location = $request->ip();
		$userprofile->salary_from = $request->salary_from;
		$userprofile->salary_to = $request->salary_to;
		$userprofile->ad_title = $request->ad_title;
		$userprofile->ad_type = 'Free';
		//$userprofile->ad_expiry     = $subscription_expiry;
		$userprofile->active_status = '0';
		//$userprofile->ad_view_count   = '0';
		$userprofile->ads_validity = $ads_validity;
		$userprofile->description = $request->description;
		$userprofile->city = $request->city;
		$userprofile->delete_status = '0';
		$userprofile->status = '0';
		//	$userprofile->save();

		$ads_id = $request->ads_id;

		if ($request->file('file')) {
			$imageName = time() . '.' . $request->file->extension();
			$request->file->move(public_path('uploads/ads'), $imageName);
			$userprofile->image = url('public/uploads/ads') . '/' . $imageName;
			$imagePath = public_path('uploads/ads/' . $imageName);
			ImageOptimizer::optimize($imagePath);
			AdPostingImage::updateOrCreate(['ads_id' => $ads_id, 'image_no' => '1'], [
				'ads_id' => $ads_id,
				'image_no' => '1',
				'image' => url('public/uploads/ads') . '/' . $imageName,
			]);

		}
		$userprofile->save();

		$adimage = $userprofile->image;

		if ($request->hasFile('file1')) {
			$file = $request->file('file1');
			$name = time() . rand(1, 100) . '.' . $file->extension();
			$file->move(public_path('uploads/ads'), $name);
			$imagePath = public_path('uploads/ads/' . $name);
			ImageOptimizer::optimize($imagePath);

			AdPostingImage::updateOrCreate(['ads_id' => $ads_id, 'image_no' => '2'], [
				'ads_id' => $ads_id,
				'image_no' => '2',
				'image' => url('public/uploads/ads') . '/' . $name,
			]);
		}

		if ($request->hasFile('file2')) {
			$file2 = $request->file('file2');
			$name2 = time() . rand(1, 100) . '.' . $file2->extension();
			$file2->move(public_path('uploads/ads'), $name2);
			$imagePath = public_path('uploads/ads/' . $name2);
			ImageOptimizer::optimize($imagePath);

			AdPostingImage::updateOrCreate(['ads_id' => $ads_id, 'image_no' => '3'], [
				'ads_id' => $ads_id,
				'image_no' => '3',
				'image' => url('public/uploads/ads') . '/' . $name2,
			]);
		}
		if ($request->hasFile('file3')) {
			$file3 = $request->file('file3');
			$name3 = time() . rand(1, 100) . '.' . $file3->extension();
			$file3->move(public_path('uploads/ads'), $name3);
			$imagePath = public_path('uploads/ads/' . $name3);
			ImageOptimizer::optimize($imagePath);

			AdPostingImage::updateOrCreate(['ads_id' => $ads_id, 'image_no' => '4'], [
				'ads_id' => $ads_id,
				'image_no' => '4',
				'image' => url('public/uploads/ads') . '/' . $name3,
			]);
		}

		if ($request->hasFile('file4')) {
			$file4 = $request->file('file4');
			$name4 = time() . rand(1, 100) . '.' . $file4->extension();
			$file4->move(public_path('uploads/ads'), $name4);
			$imagePath = public_path('uploads/ads/' . $name4);
			ImageOptimizer::optimize($imagePath);

			AdPostingImage::updateOrCreate(['ads_id' => $ads_id, 'image_no' => '5'], [
				'ads_id' => $ads_id,
				'image_no' => '5',
				'image' => url('public/uploads/ads') . '/' . $name4,
			]);
		}

		$userprofile = Jobforms::where('ads_id', $request->ads_id)->first();

		$userprofile->image = $adimage;
		$userprofile->ads_id = $ads_id;
		$userprofile->user_id = $request->user_id;
		$userprofile->category_id = $request->category_id;
		$userprofile->sub_category_id = $request->subcatid;
		$userprofile->formtype = $request->formtype;
		$userprofile->fullname = $request->fullname;
		$userprofile->email = $request->email;
		$userprofile->mobile = $request->mobile;
		$userprofile->location = $request->ip();
		$userprofile->state = $request->state;
		$userprofile->state_name = $request->state_name;
		$userprofile->city = $request->city;
		$userprofile->city_name = $request->city_name;
		$userprofile->neibourhood = $request->neibourhood;
		$userprofile->salary_period = $request->salary_period;
		$userprofile->position_type = $request->position_type;
		$userprofile->salary_from = $request->salary_from;
		$userprofile->salary_to = $request->salary_to;
		$userprofile->ad_title = $request->ad_title;
		$userprofile->ad_type = 'Free';
		$userprofile->description = $request->description;
		$userprofile->delete_status = '0';
		$userprofile->status = '0';
		$userprofile->save();

		$result_subscriber_exists1 = DB::table('subscription_orders')->where('user_id', $request->user_id)->whereDate('subscription_expiry', '>=', Carbon::now())->exists();
		if (isset($currenAdStatus) && $currenAdStatus == '2' && $result_subscriber_exists1) {


			$result_subscriber1 = DB::table('subscription_orders')->where('user_id', $request->user_id)->where('category_id', $request->category_id)->whereDate('subscription_expiry', '>=', Carbon::now())->get();
			$used_ads1 = $result_subscriber1[0]->used_ads;
			if ($used_ads1 > 0) {
				$total_ads1 = $used_ads1 - 1;
			}
			DB::table('subscription_orders')->where('user_id', $request->user_id)->where('category_id', $request->category_id)->whereDate('subscription_expiry', '>=', Carbon::now())->update(
				array(
					'used_ads' => $total_ads1,
				)
			);
		}



		// 		if($category_subscription_exists)
// 		{
// 			//$category_subscription_result = DB::table('subscription_orders')->where('category_id',$category_id)->where('user_id',$user_id)->get();
// 			$category_subscription_result = 	DB::table("subscription_history")
//         ->select("subscription_history.*")
//         ->whereRaw("find_in_set('".$category_id."',subscription_history.category_id)")
//         ->where('user_id',$user_id)
//         ->where('status','0')
//         ->get();

		// 			$used_ads 	= $category_subscription_result[0]->used_ads;
// 			$total_ads 	= $used_ads + 1;

		// 			DB::table("subscription_history")
//         ->select("subscription_history.*")
//         ->whereRaw("find_in_set('".$category_id."',subscription_history.category_id)")
//         ->where('user_id',$user_id)
//         ->where('status','0')
//         ->update(array('used_ads'=>$total_ads));

		// 			//DB::table('subscription_orders')->where('category_id',$category_id)->where('user_id',$user_id)->update(array('used_ads'=>$total_ads));
// 		}
		\Session::put('success', 'Post Updated Successfully.');
		//return redirect("user-dashboard");
		return redirect("thank-you");
	}

	public function post_mobile_forms(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'fullname' => 'required|max:50|min:0',
			'email' => 'required|max:50|min:0',
			'mobile' => 'required|max:50|min:0',
			//'location' 	=>'required|max:50|min:0',
			'brands' => 'required|max:50|min:0',
			'price' => 'required|max:50|min:0',
			'ad_title' => 'required|max:50|min:0',
			'description' => 'required|max:2500|min:0',
			'file' => 'required|image|max:8192',   // 8MB max size
			'file1' => 'nullable|image|max:8192',   // 8MB max size, nullable
			'file2' => 'nullable|image|max:8192',   // 8MB max size, nullable
			'file3' => 'nullable|image|max:8192',   // 8MB max size, nullable
			'file4' => 'nullable|image|max:8192',   // 8MB max size, nullable
		]);

		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator)->withInput();
		}

		$subscription = '0';
		$category_id = $request->category_id;
		$user_id = $request->user_id;

		$ads_postings_count = DB::table('ads_postings')->where('user_id', $user_id)->where('delete_status', 0)->count('id');
		//$category_subscription_exists = DB::table('subscription_orders')->where('category_id',$category_id)->where('user_id',$user_id)->exists();
		$pending_ads_count = DB::table('ads_postings')
			->where('user_id', $user_id)
			->where('delete_status', 0)
			->where('active_status', '0')
			->count('id');

		$category_subscription_exists = DB::table("subscription_history")
			->where('user_id', $user_id)
			->where('status', '0')
			->exists();

		$category_id_exists = DB::table('subscriptions_free_trials')->where('category_id', $category_id)->exists();
		//	if($category_id_exists)
		//	{
		$result_category_id = DB::table('subscriptions_free_trials')->where('category_id', $category_id)->get();
		$no_of_ads = $result_category_id[0]->no_of_ads;
		$active_postings_count = DB::table('ads_postings')->where('user_id', $user_id)->where('ad_type', 'Free')->where('active_status', '1')->count('id');

		$ads_validity = $result_category_id[0]->ads_validity;

		//	if($no_of_ads == $ads_postings_count  && $category_subscription_exists != 1)
		if ($category_subscription_exists != 1) {
			if ($request->hasFile('file')) {
				$imageName = time() . '.' . $request->file->extension();
				$request->file->move(public_path('uploads/ads'), $imageName);
				$imagePath = public_path('uploads/ads/' . $imageName);
				ImageOptimizer::optimize($imagePath);
				$imageSession = url('public/uploads/ads') . '/' . $imageName;
			}

			if ($request->hasFile('file1')) {
				$file = $request->file('file1');
				$name = time() . rand(1, 100) . '.' . $file->extension();
				$file->move(public_path('uploads/ads'), $name);
				$imagePath = public_path('uploads/ads/' . $name);
				ImageOptimizer::optimize($imagePath);
				$imageSession2 = url('public/uploads/ads') . '/' . $name;
			}

			if ($request->hasFile('file2')) {
				$file2 = $request->file('file2');
				$name2 = time() . rand(1, 100) . '.' . $file2->extension();
				$file2->move(public_path('uploads/ads'), $name2);
				$imagePath = public_path('uploads/ads/' . $name2);
				ImageOptimizer::optimize($imagePath);
				$imageSession3 = url('public/uploads/ads') . '/' . $name2;
			}
			if ($request->hasFile('file3')) {
				$file3 = $request->file('file3');
				$name3 = time() . rand(1, 100) . '.' . $file3->extension();
				$file3->move(public_path('uploads/ads'), $name3);
				$imagePath = public_path('uploads/ads/' . $name3);
				ImageOptimizer::optimize($imagePath);
				$imageSession4 = url('public/uploads/ads') . '/' . $name3;
			}
			if ($request->hasFile('file4')) {
				$file4 = $request->file('file4');
				$name4 = time() . rand(1, 100) . '.' . $file4->extension();
				$file4->move(public_path('uploads/ads'), $name4);
				$imagePath = public_path('uploads/ads/' . $name4);
				ImageOptimizer::optimize($imagePath);
				$imageSession5 = url('public/uploads/ads') . '/' . $name4;
			}
			Session::put('ImageAd', ['image1' => $imageSession ?? null, 'image2' => $imageSession2 ?? null, 'image3' => $imageSession3 ?? null, 'image4' => $imageSession4 ?? null,]);

			Session::put('UserProfile', [
				'image' => $imageSession,
				'ads_validity' => $ads_validity,
				'ad_id' => $rand = mt_rand(1500, 5000),
				'category_id' => $request->category_id,
				'user_id' => $request->user_id,
				'sub_category_id' => $request->subcatid,
				'formtype' => $request->formtype,
				'fullname' => $request->fullname,
				'email' => $request->email,
				'mobile' => $request->mobile,
				'location' => $request->ip(),
				'city' => $request->city,
				'price' => $request->price,
				'ad_title' => $request->ad_title,
				'ad_type' => 'Free',
				'active_status' => '0',
				'ad_view_count' => '0',
				'description' => $request->description,
				'delete_status' => '0',
				'status' => '0'
			]);

			Session::put('MobileDetail', [
				'user_id' => $request->user_id,
				'category_id' => $request->category_id,
				'sub_category_id' => $request->subcatid,
				'formtype' => $request->formtype,
				'fullname' => $request->fullname,
				'email' => $request->email,
				'mobile' => $request->mobile,
				'location' => $request->ip(),
				'state' => $request->state,
				'state_name' => $request->state_name,
				'city' => $request->city,
				'city_name' => $request->city_name,
				'neibourhood' => $request->neibourhood,
				'brand' => $request->brands,
				'ad_title' => $request->ad_title,
				'ad_type' => 'Free',
				'description' => $request->description,
				'delete_status' => '0',
				'status' => '0'
			]);

			$event = DefaultNotification::where('event', 'free_limit')->first();
			if (!empty($event)) {
				$title = $event->title;
				$body = $event->content;
				$notifyArray = array(
					'user_id' => $user_id,
					'event_name' => $event->event,
					'title' => $title,
					'body' => $body,
				);

				$this->singleUserNotification($notifyArray);
			}

			\Session::put('success', 'No More Free Ads Purchase Subscription For More Posting.');
			return redirect()->route('purchase-subscription');
		}
		//	}

		if ($category_subscription_exists) {
			//$category_subscription_result = DB::table('subscription_orders')->where('category_id',$category_id)->where('user_id',$user_id)->get();

			$category_subscription_result = DB::table("subscription_history")
				->where('user_id', $user_id)
				->where('status', '0')
				->orderBy('created_at', 'DESC')
				->get();

			$payment_status = $category_subscription_result[0]->payment_status;
			$used_ads = $category_subscription_result[0]->used_ads;
			$remaining_ads = $category_subscription_result[0]->remaining_ads;
			$status = $category_subscription_result[0]->status;

			if ($payment_status != 'Completed') {

				\Session::put('success', 'Payment Is Pending');
				return redirect()->route('purchase-subscription');
			}

			$usedAdsTotal = 0;
			$remainingAdsTotal = 0;

			foreach ($category_subscription_result as $subscription) {
				$usedAdsTotal += $subscription->used_ads;
				$remainingAdsTotal += $subscription->remaining_ads;
			}

			if ($ads_postings_count >= $remainingAdsTotal) {
				if ($request->hasFile('file')) {
					$imageName = time() . '.' . $request->file->extension();
					$request->file->move(public_path('uploads/ads'), $imageName);
					$imagePath = public_path('uploads/ads/' . $imageName);
					ImageOptimizer::optimize($imagePath);
					$imageSession = url('public/uploads/ads') . '/' . $imageName;
				}

				if ($request->hasFile('file1')) {
					$file = $request->file('file1');
					$name = time() . rand(1, 100) . '.' . $file->extension();
					$file->move(public_path('uploads/ads'), $name);
					$imagePath = public_path('uploads/ads/' . $name);
					ImageOptimizer::optimize($imagePath);
					$imageSession2 = url('public/uploads/ads') . '/' . $name;
				}

				if ($request->hasFile('file2')) {
					$file2 = $request->file('file2');
					$name2 = time() . rand(1, 100) . '.' . $file2->extension();
					$file2->move(public_path('uploads/ads'), $name2);
					$imagePath = public_path('uploads/ads/' . $name2);
					ImageOptimizer::optimize($imagePath);
					$imageSession3 = url('public/uploads/ads') . '/' . $name2;
				}
				if ($request->hasFile('file3')) {
					$file3 = $request->file('file3');
					$name3 = time() . rand(1, 100) . '.' . $file3->extension();
					$file3->move(public_path('uploads/ads'), $name3);
					$imagePath = public_path('uploads/ads/' . $name3);
					ImageOptimizer::optimize($imagePath);
					$imageSession4 = url('public/uploads/ads') . '/' . $name3;
				}
				if ($request->hasFile('file4')) {
					$file4 = $request->file('file4');
					$name4 = time() . rand(1, 100) . '.' . $file4->extension();
					$file4->move(public_path('uploads/ads'), $name4);
					$imagePath = public_path('uploads/ads/' . $name4);
					ImageOptimizer::optimize($imagePath);
					$imageSession5 = url('public/uploads/ads') . '/' . $name4;
				}

				Session::put('ImageAd', ['image1' => $imageSession ?? null, 'image2' => $imageSession2 ?? null, 'image3' => $imageSession3 ?? null, 'image4' => $imageSession4 ?? null,]);

				Session::put('UserProfile', [
					'image' => $imageSession,
					'ads_validity' => $ads_validity,
					'ad_id' => $rand = mt_rand(1500, 5000),
					'category_id' => $request->category_id,
					'user_id' => $request->user_id,
					'sub_category_id' => $request->subcatid,
					'formtype' => $request->formtype,
					'fullname' => $request->fullname,
					'email' => $request->email,
					'mobile' => $request->mobile,
					'location' => $request->ip(),
					'city' => $request->city,
					'price' => $request->price,
					'ad_title' => $request->ad_title,
					'ad_type' => 'Free',
					'active_status' => '0',
					'ad_view_count' => '0',
					'description' => $request->description,
					'delete_status' => '0',
					'status' => '0'
				]);

				Session::put('CommonDetail', [
					'user_id' => $request->user_id,
					'category_id' => $request->category_id,
					'sub_category_id' => $request->subcatid,
					'formtype' => $request->formtype,
					'fullname' => $request->fullname,
					'email' => $request->email,
					'mobile' => $request->mobile,
					'location' => $request->ip(),
					'state' => $request->state,
					'state_name' => $request->state_name,
					'city' => $request->city,
					'city_name' => $request->city_name,
					'neibourhood' => $request->neibourhood,
					'price' => $request->price,
					'ad_title' => $request->ad_title,
					'ad_type' => 'Free',
					'description' => $request->description
					,
					'delete_status' => '0',
					'status' => '0'
				]);
				\Session::put('success', 'All Ads Are Used in the bucket, please empty the bucket or buy new subscription.');
				return redirect()->route('purchase-subscription');
			}

			if ($status == '1') {

				\Session::put('success', 'Subscription Validity Expired');
				return redirect()->route('purchase-subscription');
			}
		}

		$no = $ads_validity;
		$dates = date("d-m-Y");
		$date = date_create($dates);

		date_add($date, date_interval_create_from_date_string($no . "days"));
		$subscription_expiry = date_format($date, "d-m-Y");

		$customerprofile = Customer::find($user_id);

		if ($request->file('changeprofile')) {
			$imageName = time() . '.' . $request->changeprofile->extension();
			$request->changeprofile->move(public_path('uploads/ads'), $imageName);
			$customerprofile->image = url('public/uploads/ads') . '/' . $imageName;

		} else {
			$customerprofile->image = url('public/uploads/ads/dummy.jpeg');
		}

		if ($request->file('userprofile')) {
			$imageName = time() . '.' . $request->userprofile->extension();
			$request->userprofile->move(public_path('uploads/ads'), $imageName);
			$customerprofile->image = url('public/uploads/ads') . '/' . $imageName;

		} else {
			$customerprofile->image = url('public/uploads/ads/dummy.jpeg');
		}

		$customerprofile->save();

		if (isset($request->is_mobile_hide)) {
			$hide_mobile = '1';
		} else {
			$hide_mobile = '0';
		}

		$userprofile = new Adposting;

		$userprofile->ad_id = $rand = mt_rand(1500, 5000);
		$userprofile->user_id = $request->user_id;
		$userprofile->subscription_id = $category_subscription_result[0]->id;
		$userprofile->category_id = $request->category_id;
		$userprofile->sub_category_id = $request->subcatid;
		$userprofile->formtype = $request->formtype;
		$userprofile->fullname = $request->fullname;
		$userprofile->email = $request->email;
		$userprofile->mobile = $request->mobile;
		$userprofile->is_mobile_hide = $hide_mobile;
		$userprofile->location = $request->ip();
		$userprofile->city = $request->city;
		$userprofile->price = $request->price;
		$userprofile->ad_title = $request->ad_title;
		$subscriptionRaw = Subscription::where('id', $category_subscription_result[0]->subscription_id)->where('delete_status', '0')->where('status', '0')->first();
		if ($subscriptionRaw->is_free == 'yes') {
			$userprofile->ad_type = 'Free';
		} else {
			$userprofile->ad_type = 'Paid';
		}
		//$userprofile->ad_expiry 		= $subscription_expiry;
		$userprofile->active_status = '0';
		$userprofile->ad_view_count = '0';
		$userprofile->ads_validity = $ads_validity;
		$userprofile->description = $request->description;
		$userprofile->delete_status = '0';
		$userprofile->status = '0';
		//	$userprofile->save();

		$ads_id = $userprofile->ad_id;

		if ($request->hasFile('file')) {
			$imageName = time() . '.' . $request->file->extension();
			$request->file->move(public_path('uploads/ads'), $imageName);
			$imagePath = public_path('uploads/ads/' . $imageName);
			ImageOptimizer::optimize($imagePath);
			$userprofile->image = url('public/uploads/ads') . '/' . $imageName;
			AdPostingImage::create([
				'ads_id' => $ads_id,
				'image' => url('public/uploads/ads') . '/' . $imageName,
				'image_no' => '1'
			]);

		} else {
			$userprofile->image = url('public/uploads/ads/dummy.jpeg');
		}
		$userprofile->save();

		$adimage = $userprofile->image;

		if ($request->hasFile('file1')) {
			$file = $request->file('file1');
			$name = time() . rand(1, 100) . '.' . $file->extension();
			$file->move(public_path('uploads/ads'), $name);
			$imagePath = public_path('uploads/ads/' . $name);
			ImageOptimizer::optimize($imagePath);
			AdPostingImage::create([
				'ads_id' => $ads_id,
				'image' => url('public/uploads/ads') . '/' . $name,
				'image_no' => '2'
			]);
		}

		if ($request->hasFile('file2')) {
			$file2 = $request->file('file2');
			$name2 = time() . rand(1, 100) . '.' . $file2->extension();
			$file2->move(public_path('uploads/ads'), $name2);
			$imagePath = public_path('uploads/ads/' . $name2);
			ImageOptimizer::optimize($imagePath);
			AdPostingImage::create([
				'ads_id' => $ads_id,
				'image' => url('public/uploads/ads') . '/' . $name2,
				'image_no' => '3'
			]);
		}
		if ($request->hasFile('file3')) {
			$file3 = $request->file('file3');
			$name3 = time() . rand(1, 100) . '.' . $file3->extension();
			$file3->move(public_path('uploads/ads'), $name3);
			$imagePath = public_path('uploads/ads/' . $name3);
			ImageOptimizer::optimize($imagePath);
			AdPostingImage::create([
				'ads_id' => $ads_id,
				'image' => url('public/uploads/ads') . '/' . $name3,
				'image_no' => '4'
			]);
		}

		if ($request->hasFile('file4')) {
			$file4 = $request->file('file4');
			$name4 = time() . rand(1, 100) . '.' . $file4->extension();
			$file4->move(public_path('uploads/ads'), $name4);
			$imagePath = public_path('uploads/ads/' . $name4);
			ImageOptimizer::optimize($imagePath);
			AdPostingImage::create([
				'ads_id' => $ads_id,
				'image' => url('public/uploads/ads') . '/' . $name4,
				'image_no' => '5'
			]);
		}

		$userprofile = new Mobileform;

		$userprofile->image = $adimage;
		$userprofile->ads_id = $ads_id;
		$userprofile->user_id = $request->user_id;
		$userprofile->category_id = $request->category_id;
		$userprofile->sub_category_id = $request->subcatid;
		$userprofile->formtype = $request->formtype;
		$userprofile->fullname = $request->fullname;
		$userprofile->email = $request->email;
		$userprofile->mobile = $request->mobile;
		$userprofile->location = $request->ip();
		$userprofile->state = $request->state;
		$userprofile->state_name = $request->state_name;
		$userprofile->city = $request->city;
		$userprofile->city_name = $request->city_name;
		$userprofile->neibourhood = $request->neibourhood;
		$userprofile->brand = $request->brands;
		$userprofile->price = $request->price;
		$userprofile->ad_title = $request->ad_title;
		if ($subscriptionRaw->is_free == 'yes') {
			$userprofile->ad_type = 'Free';
		} else {
			$userprofile->ad_type = 'Paid';
		}
		$userprofile->description = $request->description;
		$userprofile->delete_status = '0';
		$userprofile->status = '0';
		$userprofile->save();

		// 		if($category_subscription_exists)
// 		{
// 			//$category_subscription_result = DB::table('subscription_orders')->where('category_id',$category_id)->where('user_id',$user_id)->get();

		// 			$category_subscription_result = 	DB::table("subscription_history")
//             ->select("subscription_history.*")
//             ->whereRaw("find_in_set('".$category_id."',subscription_history.category_id)")
//             ->where('user_id',$user_id)
//             ->where('status','0')
//             ->get();

		// 			$used_ads 	= $category_subscription_result[0]->used_ads;
// 			$total_ads 	= $used_ads + 1;

		// 			DB::table("subscription_history")
//             ->select("subscription_history.*")
//             ->whereRaw("find_in_set('".$category_id."',subscription_history.category_id)")
//             ->where('user_id',$user_id)
//             ->where('status','0')
//             ->update(array('used_ads'=>$total_ads));
// 		//	DB::table('subscription_orders')->where('category_id',$category_id)->where('user_id',$user_id)->update(array('used_ads'=>$total_ads));
// 		}

		\Session::put('success', 'Post Added Successfully.');
		//return redirect("user-dashboard");
		return redirect("thank-you");
	}

	public function edit_post_mobile_forms(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'fullname' => 'required|max:50|min:0',
			'email' => 'required|max:50|min:0',
			//	'mobile' 		=>'required|max:50|min:0',
			//'location' 	=>'required|max:50|min:0',
			'brands' => 'required|max:50|min:0',
			'price' => 'required|max:50|min:0',
			'ad_title' => 'required|max:50|min:0',
			'description' => 'required|max:2500|min:0',
		]);

		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator)->withInput();
		}

		$subscription = '0';
		$category_id = $request->category_id;
		$user_id = $request->user_id;

		$ads_postings_count = DB::table('ads_postings')->where('user_id', $user_id)->where('ad_type', 'Free')->where('category_id', $category_id)->count('id');
		//	$category_subscription_exists = DB::table('subscription_orders')->where('category_id',$category_id)->where('user_id',$user_id)->exists();

		$category_subscription_exists = DB::table("subscription_history")
			->where('user_id', $user_id)
			->where('status', '0')
			->exists();
		$category_id_exists = DB::table('subscriptions_free_trials')->where('category_id', $category_id)->exists();
		if ($category_id_exists) {
			$result_category_id = DB::table('subscriptions_free_trials')->where('category_id', $category_id)->get();
			$no_of_ads = $result_category_id[0]->no_of_ads;
			$ads_validity = $result_category_id[0]->ads_validity;

			// if($no_of_ads == $ads_postings_count  && $category_subscription_exists != 1)
			// {
			// 	\Session::put('success','No More Free Ads Purchase Subscription For More Posting.');
			// 	return redirect()->route('purchase-subscription');
			// }
		}

		if ($category_subscription_exists) {
			//	$category_subscription_result = DB::table('subscription_orders')->where('category_id',$category_id)->where('user_id',$user_id)->get();

			$category_subscription_result = DB::table("subscription_history")
				->where('user_id', $user_id)
				->where('status', '0')
				->orderBy('created_at', 'DESC')
				->get();

			$payment_status = $category_subscription_result[0]->payment_status;
			$used_ads = $category_subscription_result[0]->used_ads;
			$remaining_ads = $category_subscription_result[0]->remaining_ads;
			$status = $category_subscription_result[0]->status;

			// 			if($payment_status != 'Completed')
// 			{
// 				\Session::put('success','Payment Is Pending');
// 				return redirect()->route('purchase-subscription');
// 			}

			// 			if($used_ads == $remaining_ads)
// 			{
// 				\Session::put('success','All Ads Are Used.');
// 				return redirect()->route('purchase-subscription');
// 			}
			if ($status == '1') {
				\Session::put('success', 'Subscription Validity Expired');
				return redirect()->route('purchase-subscription');
			}
		}

		$no = $ads_validity;
		$dates = date("d-m-Y");
		$date = date_create($dates);

		date_add($date, date_interval_create_from_date_string($no . "days"));
		$subscription_expiry = date_format($date, "d-m-Y");

		$customerprofile = Customer::find($user_id);

		if ($request->file('changeprofile')) {
			$imageName = time() . '.' . $request->changeprofile->extension();
			$request->changeprofile->move(public_path('uploads/ads'), $imageName);
			$customerprofile->image = url('public/uploads/ads') . '/' . $imageName;

		} else {
			$customerprofile->image = url('public/uploads/ads/dummy.jpeg');
		}

		if ($request->file('userprofile')) {
			$imageName = time() . '.' . $request->userprofile->extension();
			$request->userprofile->move(public_path('uploads/ads'), $imageName);
			$customerprofile->image = url('public/uploads/ads') . '/' . $imageName;

		} else {
			$customerprofile->image = url('public/uploads/ads/dummy.jpeg');
		}

		$customerprofile->save();

		if (isset($request->is_mobile_hide)) {
			$hide_mobile = '1';
		} else {
			$hide_mobile = '0';
		}

		$userprofile = Adposting::find($request->ad_id);

		if (isset($request->mobile)) {
			$m_mobile = $request->mobile;
		} else {
			$m_mobile = $userprofile->mobile;
		}

		$userprofile->ad_id = $request->ads_id;
		$userprofile->user_id = $request->user_id;
		$userprofile->category_id = $request->category_id;
		$userprofile->sub_category_id = $request->subcatid;
		$userprofile->formtype = $request->formtype;
		$userprofile->fullname = $request->fullname;
		$userprofile->email = $request->email;
		$userprofile->mobile = $m_mobile;
		$userprofile->is_mobile_hide = $hide_mobile;
		$userprofile->location = $request->ip();
		$userprofile->city = $request->city;
		$userprofile->price = $request->price;
		$userprofile->ad_title = $request->ad_title;
		$subscriptionRaw = Subscription::where('id', $category_subscription_result[0]->subscription_id)->where('delete_status', '0')->where('status', '0')->first();
		if ($subscriptionRaw->is_free == 'yes') {
			$userprofile->ad_type = 'Free';
		} else {
			$userprofile->ad_type = 'Paid';
		}
		//$userprofile->ad_expiry 		= $subscription_expiry;
		$userprofile->active_status = '0';
		//$userprofile->ad_view_count   = '0';
		$userprofile->ads_validity = $ads_validity;
		$userprofile->description = $request->description;
		$userprofile->delete_status = '0';
		$userprofile->status = '0';
		//	$userprofile->save();

		$ads_id = $request->ads_id;

		if ($request->file('file')) {
			$imageName = time() . '.' . $request->file->extension();
			$request->file->move(public_path('uploads/ads'), $imageName);
			$userprofile->image = url('public/uploads/ads') . '/' . $imageName;
			$imagePath = public_path('uploads/ads/' . $imageName);
			ImageOptimizer::optimize($imagePath);
			AdPostingImage::updateOrCreate(['ads_id' => $ads_id, 'image_no' => '1'], [
				'ads_id' => $ads_id,
				'image_no' => '1',
				'image' => url('public/uploads/ads') . '/' . $imageName,
			]);

		}
		$userprofile->save();

		$adimage = $userprofile->image;

		if ($request->hasFile('file1')) {
			$file = $request->file('file1');
			$name = time() . rand(1, 100) . '.' . $file->extension();
			$file->move(public_path('uploads/ads'), $name);
			$imagePath = public_path('uploads/ads/' . $name);
			ImageOptimizer::optimize($imagePath);

			AdPostingImage::updateOrCreate(['ads_id' => $ads_id, 'image_no' => '2'], [
				'ads_id' => $ads_id,
				'image_no' => '2',
				'image' => url('public/uploads/ads') . '/' . $name,
			]);
		}

		if ($request->hasFile('file2')) {
			$file2 = $request->file('file2');
			$name2 = time() . rand(1, 100) . '.' . $file2->extension();
			$file2->move(public_path('uploads/ads'), $name2);
			$imagePath = public_path('uploads/ads/' . $name2);
			ImageOptimizer::optimize($imagePath);

			AdPostingImage::updateOrCreate(['ads_id' => $ads_id, 'image_no' => '3'], [
				'ads_id' => $ads_id,
				'image_no' => '3',
				'image' => url('public/uploads/ads') . '/' . $name2,
			]);
		}
		if ($request->hasFile('file3')) {
			$file3 = $request->file('file3');
			$name3 = time() . rand(1, 100) . '.' . $file3->extension();
			$file3->move(public_path('uploads/ads'), $name3);
			$imagePath = public_path('uploads/ads/' . $name3);
			ImageOptimizer::optimize($imagePath);

			AdPostingImage::updateOrCreate(['ads_id' => $ads_id, 'image_no' => '4'], [
				'ads_id' => $ads_id,
				'image_no' => '4',
				'image' => url('public/uploads/ads') . '/' . $name3,
			]);
		}

		if ($request->hasFile('file4')) {
			$file4 = $request->file('file4');
			$name4 = time() . rand(1, 100) . '.' . $file4->extension();
			$file4->move(public_path('uploads/ads'), $name4);
			$imagePath = public_path('uploads/ads/' . $name4);
			ImageOptimizer::optimize($imagePath);

			AdPostingImage::updateOrCreate(['ads_id' => $ads_id, 'image_no' => '5'], [
				'ads_id' => $ads_id,
				'image_no' => '5',
				'image' => url('public/uploads/ads') . '/' . $name4,
			]);
		}

		$userprofile = Mobileform::where('ads_id', $request->ads_id)->first();

		$userprofile->image = $adimage;
		$userprofile->ads_id = $ads_id;
		$userprofile->user_id = $request->user_id;
		$userprofile->category_id = $request->category_id;
		$userprofile->sub_category_id = $request->subcatid;
		$userprofile->formtype = $request->formtype;
		$userprofile->fullname = $request->fullname;
		$userprofile->email = $request->email;
		$userprofile->mobile = $request->mobile;
		$userprofile->location = $request->ip();
		$userprofile->state = $request->state;
		$userprofile->state_name = $request->state_name;
		$userprofile->city = $request->city;
		$userprofile->city_name = $request->city_name;
		$userprofile->neibourhood = $request->neibourhood;
		$userprofile->brand = $request->brands;
		$userprofile->price = $request->price;
		$userprofile->ad_title = $request->ad_title;
		if ($subscriptionRaw->is_free == 'yes') {
			$userprofile->ad_type = 'Free';
		} else {
			$userprofile->ad_type = 'Paid';
		}
		$userprofile->description = $request->description;
		$userprofile->delete_status = '0';
		$userprofile->status = '0';
		$userprofile->save();

		// 		if($category_subscription_exists)
// 		{
// 			//$category_subscription_result = DB::table('subscription_orders')->where('category_id',$category_id)->where('user_id',$user_id)->get();

		//             $category_subscription_result = 	DB::table("subscription_history")
//         ->select("subscription_history.*")
//         ->whereRaw("find_in_set('".$category_id."',subscription_history.category_id)")
//         ->where('user_id',$user_id)
//         ->where('status','0')
//         ->get();

		// 			$used_ads 	= $category_subscription_result[0]->used_ads;
// 			$total_ads 	= $used_ads + 1;

		// 			DB::table("subscription_history")
//         ->select("subscription_history.*")
//         ->whereRaw("find_in_set('".$category_id."',subscription_history.category_id)")
//         ->where('user_id',$user_id)
//         ->where('status','0')
//         ->update(array('used_ads'=>$total_ads));

		// 			//DB::table('subscription_orders')->where('category_id',$category_id)->where('user_id',$user_id)->update(array('used_ads'=>$total_ads));
// 		}

		\Session::put('success', 'Post Updated Successfully.');
		//return redirect("user-dashboard");
		return redirect("thank-you");
	}

	public function post_vehicle_forms(Request $request)
	{
		//echo "<pre/>"; print_r($request->all()); die('kjerbgfje');
		$validator = Validator::make($request->all(), [
			'brands' => 'required',
			'vehicle_type' => 'required',
			'fuel_type' => 'required',
			'transmission' => 'required',
			'year' => 'required',
			'fullname' => 'required|max:50|min:0',
			'email' => 'required|max:50|min:0',
			'file' => 'required|image|max:8192',   // 8MB max size
			'file1' => 'nullable|image|max:8192',   // 8MB max size, nullable
			'file2' => 'nullable|image|max:8192',   // 8MB max size, nullable
			'file3' => 'nullable|image|max:8192',   // 8MB max size, nullable
			'file4' => 'nullable|image|max:8192',   // 8MB max size, nullable
			'price' => 'required|max:50|min:0',
			'ad_title' => 'required|max:50|min:0',
			'description' => 'required|max:2500|min:0',
		]);

		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator)->withInput();
		}

		$subscription = '0';
		$category_id = $request->category_id;
		$user_id = $request->user_id;

		$ads_postings_count = DB::table('ads_postings')->where('user_id', $user_id)->where('delete_status', 0)->count('id');
		// echo"<pre/>"; print_r($ads_postings_count); die('zdfbhjvbdbvkjdbskjcbkjs');
		//$category_subscription_exists = DB::table('subscription_orders')->where('category_id', 'NOT LIKE', '%'.$category_id.'%')->where('user_id','=',$user_id)->get();
		//echo"<pre/>"; print_r($ads_postings_count); die('zdfbhjvbdbvkjdbskjcbkjs');
		$pending_ads_count = DB::table('ads_postings')
			->where('user_id', $user_id)
			->where('delete_status', 0)
			->where('active_status', '0')
			->count('id');

		$category_subscription_exists = DB::table("subscription_history")
			->where('user_id', $user_id)
			->where('status', '0')
			->exists();
		$category_id_exists = DB::table('subscriptions_free_trials')->where('category_id', $category_id)->exists();
		$result_category_id = DB::table('subscriptions_free_trials')->where('category_id', $category_id)->get();
		$no_of_ads = $result_category_id[0]->no_of_ads;
		$ads_validity = $result_category_id[0]->ads_validity;
		if ($category_subscription_exists != 1) {

			if ($request->hasFile('file')) {
				$imageName = time() . '.' . $request->file->extension();
				$request->file->move(public_path('uploads/ads'), $imageName);
				$imagePath = public_path('uploads/ads/' . $imageName);
				ImageOptimizer::optimize($imagePath);
				$imageSession = url('public/uploads/ads') . '/' . $imageName;
			}

			if ($request->hasFile('file1')) {
				$file = $request->file('file1');
				$name = time() . rand(1, 100) . '.' . $file->extension();
				$file->move(public_path('uploads/ads'), $name);
				$imagePath = public_path('uploads/ads/' . $name);
				ImageOptimizer::optimize($imagePath);
				$imageSession2 = url('public/uploads/ads') . '/' . $name;
			}

			if ($request->hasFile('file2')) {
				$file2 = $request->file('file2');
				$name2 = time() . rand(1, 100) . '.' . $file2->extension();
				$file2->move(public_path('uploads/ads'), $name2);
				$imagePath = public_path('uploads/ads/' . $name2);
				ImageOptimizer::optimize($imagePath);
				$imageSession3 = url('public/uploads/ads') . '/' . $name2;
			}
			if ($request->hasFile('file3')) {
				$file3 = $request->file('file3');
				$name3 = time() . rand(1, 100) . '.' . $file3->extension();
				$file3->move(public_path('uploads/ads'), $name3);
				$imagePath = public_path('uploads/ads/' . $name3);
				ImageOptimizer::optimize($imagePath);
				$imageSession4 = url('public/uploads/ads') . '/' . $name3;
			}
			if ($request->hasFile('file4')) {
				$file4 = $request->file('file4');
				$name4 = time() . rand(1, 100) . '.' . $file4->extension();
				$file4->move(public_path('uploads/ads'), $name4);
				$imagePath = public_path('uploads/ads/' . $name4);
				ImageOptimizer::optimize($imagePath);
				$imageSession5 = url('public/uploads/ads') . '/' . $name4;
			}

			Session::put('ImageAd', ['image1' => $imageSession ?? null, 'image2' => $imageSession2 ?? null, 'image3' => $imageSession3 ?? null, 'image4' => $imageSession4 ?? null,]);

			Session::put('UserProfile', [
				'image' => $imageSession,
				'ads_validity' => $ads_validity,
				'ad_id' => $rand = mt_rand(1500, 5000),
				'category_id' => $request->category_id,
				'user_id' => $request->user_id,
				'sub_category_id' => $request->subcatid,
				'formtype' => $request->formtype,
				'fullname' => $request->fullname,
				'email' => $request->email,
				'mobile' => $request->mobile,
				'location' => $request->ip(),
				'city' => $request->city,
				'price' => $request->price,
				'ad_title' => $request->ad_title,
				'ad_type' => 'Free',
				'active_status' => '0',
				'ad_view_count' => '0',
				'description' => $request->description,
				'delete_status' => '0',
				'status' => '0'
			]);

			Session::put('VehicleDetail', [
				'user_id' => $request->user_id,
				'category_id' => $request->category_id,
				'sub_category_id' => $request->subcatid,
				'formtype' => $request->formtype,
				'fullname' => $request->fullname,
				'email' => $request->email,
				'mobile' => $request->mobile,
				'location' => $request->ip(),
				'state' => $request->state,
				'state_name' => $request->state_name,
				'city' => $request->city,
				'city_name' => $request->city_name,
				'neibourhood' => $request->neibourhood,
				'price' => $request->price,
				'ad_title' => $request->ad_title,
				'ad_type' => 'Free',
				'description' => $request->description,
				'brand' => $request->brands,
				'vehicle_type' => $request->vehicle_type,
				'fuel_type' => $request->fuel_type,
				'transmission' => $request->transmission,
				'year' => $request->year,
				'km' => $request->km,
				'delete_status' => '0',
				'status' => '0'
			]);

			$event = DefaultNotification::where('event', 'free_limit')->first();
			if (!empty($event)) {
				$title = $event->title;
				$body = $event->content;
				$notifyArray = array(
					'user_id' => $user_id,
					'event_name' => $event->event,
					'title' => $title,
					'body' => $body,
				);

				$this->singleUserNotification($notifyArray);
			}
			\Session::put('success', 'No More Free Ads Purchase Subscription For More Posting.');
			return redirect()->route('purchase-subscription');
		}

		if ($category_subscription_exists) {


			$category_subscription_result = DB::table("subscription_history")
				->where('user_id', $user_id)
				->where('status', '0')
				->orderBy('created_at', 'DESC')
				->get();

			$payment_status = $category_subscription_result[0]->payment_status;
			$used_ads = $category_subscription_result[0]->used_ads;
			$active_postings_count = DB::table('ads_postings')->where('user_id', $user_id)->where('ad_type', 'Free')->where('active_status', '1')->count('id');
			$remaining_ads = $category_subscription_result[0]->remaining_ads;
			$status = $category_subscription_result[0]->status;

			if ($payment_status != 'Completed') {

				\Session::put('success', 'Payment Is Pending');
				return redirect()->route('purchase-subscription');
			}

			$usedAdsTotal = 0;
			$remainingAdsTotal = 0;

			foreach ($category_subscription_result as $subscription) {
				$usedAdsTotal += $subscription->used_ads;
				$remainingAdsTotal += $subscription->remaining_ads;
			}
			if ($ads_postings_count >= $remainingAdsTotal) {
				if ($request->hasFile('file')) {
					$imageName = time() . '.' . $request->file->extension();
					$request->file->move(public_path('uploads/ads'), $imageName);
					$imagePath = public_path('uploads/ads/' . $imageName);
					ImageOptimizer::optimize($imagePath);
					$imageSession = url('public/uploads/ads') . '/' . $imageName;
				}

				if ($request->hasFile('file1')) {
					$file = $request->file('file1');
					$name = time() . rand(1, 100) . '.' . $file->extension();
					$file->move(public_path('uploads/ads'), $name);
					$imagePath = public_path('uploads/ads/' . $name);
					ImageOptimizer::optimize($imagePath);
					$imageSession2 = url('public/uploads/ads') . '/' . $name;
				}

				if ($request->hasFile('file2')) {
					$file2 = $request->file('file2');
					$name2 = time() . rand(1, 100) . '.' . $file2->extension();
					$file2->move(public_path('uploads/ads'), $name2);
					$imagePath = public_path('uploads/ads/' . $name2);
					ImageOptimizer::optimize($imagePath);
					$imageSession3 = url('public/uploads/ads') . '/' . $name2;
				}
				if ($request->hasFile('file3')) {
					$file3 = $request->file('file3');
					$name3 = time() . rand(1, 100) . '.' . $file3->extension();
					$file3->move(public_path('uploads/ads'), $name3);
					$imagePath = public_path('uploads/ads/' . $name3);
					ImageOptimizer::optimize($imagePath);
					$imageSession4 = url('public/uploads/ads') . '/' . $name3;
				}
				if ($request->hasFile('file4')) {
					$file4 = $request->file('file4');
					$name4 = time() . rand(1, 100) . '.' . $file4->extension();
					$file4->move(public_path('uploads/ads'), $name4);
					$imagePath = public_path('uploads/ads/' . $name4);
					ImageOptimizer::optimize($imagePath);
					$imageSession5 = url('public/uploads/ads') . '/' . $name4;
				}

				Session::put('ImageAd', ['image1' => $imageSession ?? null, 'image2' => $imageSession2 ?? null, 'image3' => $imageSession3 ?? null, 'image4' => $imageSession4 ?? null,]);

				Session::put('UserProfile', [
					'image' => $imageSession,
					'ads_validity' => $ads_validity,
					'ad_id' => $rand = mt_rand(1500, 5000),
					'category_id' => $request->category_id,
					'user_id' => $request->user_id,
					'sub_category_id' => $request->subcatid,
					'formtype' => $request->formtype,
					'fullname' => $request->fullname,
					'email' => $request->email,
					'mobile' => $request->mobile,
					'location' => $request->ip(),
					'city' => $request->city,
					'price' => $request->price,
					'ad_title' => $request->ad_title,
					'ad_type' => 'Free',
					'active_status' => '0',
					'ad_view_count' => '0',
					'description' => $request->description,
					'delete_status' => '0',
					'status' => '0'
				]);

				Session::put('CommonDetail', [
					'user_id' => $request->user_id,
					'category_id' => $request->category_id,
					'sub_category_id' => $request->subcatid,
					'formtype' => $request->formtype,
					'fullname' => $request->fullname,
					'email' => $request->email,
					'mobile' => $request->mobile,
					'location' => $request->ip(),
					'state' => $request->state,
					'state_name' => $request->state_name,
					'city' => $request->city,
					'city_name' => $request->city_name,
					'neibourhood' => $request->neibourhood,
					'price' => $request->price,
					'ad_title' => $request->ad_title,
					'ad_type' => 'Free',
					'description' => $request->description
					,
					'delete_status' => '0',
					'status' => '0'
				]);
				\Session::put('success', 'All Ads Are Used in the bucket, please empty the bucket or buy new subscription.');
				return redirect()->route('purchase-subscription');
			}

			if ($status == '1') {

				Session::put('UserProfile', [
					'ads_validity' => $ads_validity,
					'ad_id' => $rand = mt_rand(1500, 5000),
					'category_id' => $request->category_id,
					'user_id' => $request->user_id,
					'sub_category_id' => $request->subcatid,
					'formtype' => $request->formtype,
					'fullname' => $request->fullname,
					'email' => $request->email,
					'mobile' => $request->mobile,
					'location' => $request->ip(),
					'city' => $request->city,
					'price' => $request->price,
					'ad_title' => $request->ad_title,
					'ad_type' => 'Free',
					'active_status' => '0',
					'ad_view_count' => '0',
					'description' => $request->description,
					'delete_status' => '0',
					'status' => '0'
				]);

				Session::put('VehicleDetail', [
					'user_id' => $request->user_id,
					'category_id' => $request->category_id,
					'sub_category_id' => $request->subcatid,
					'formtype' => $request->formtype,
					'fullname' => $request->fullname,
					'email' => $request->email,
					'mobile' => $request->mobile,
					'location' => $request->ip(),
					'state' => $request->state,
					'state_name' => $request->state_name,
					'city' => $request->city,
					'city_name' => $request->city_name,
					'neibourhood' => $request->neibourhood,
					'price' => $request->price,
					'ad_title' => $request->ad_title,
					'ad_type' => 'Free',
					'description' => $request->description,
					'brand' => $request->brands,
					'vehicle_type' => $request->vehicle_type,
					'fuel_type' => $request->fuel_type,
					'transmission' => $request->transmission,
					'year' => $request->year,
					'km' => $request->km,
					'delete_status' => '0',
					'status' => '0'
				]);

				\Session::put('success', 'Subscription Validity Expired');
				return redirect()->route('purchase-subscription');
			}
		}
		$no = $ads_validity;
		$dates = date("d-m-Y");
		$date = date_create($dates);

		date_add($date, date_interval_create_from_date_string($no . "days"));
		$subscription_expiry = date_format($date, "d-m-Y");

		$customerprofile = Customer::find($user_id);

		if ($request->file('changeprofile')) {
			$imageName = time() . '.' . $request->changeprofile->extension();
			$request->changeprofile->move(public_path('uploads/ads'), $imageName);
			$customerprofile->image = url('public/uploads/ads') . '/' . $imageName;

		} else {
			$customerprofile->image = url('public/uploads/ads/dummy.jpeg');
		}

		if ($request->file('userprofile')) {
			$imageName = time() . '.' . $request->userprofile->extension();
			$request->userprofile->move(public_path('uploads/ads'), $imageName);
			$customerprofile->image = url('public/uploads/ads') . '/' . $imageName;

		} else {
			$customerprofile->image = url('public/uploads/ads/dummy.jpeg');
		}

		if (isset($request->is_mobile_hide)) {
			$hide_mobile = '1';
		} else {
			$hide_mobile = '0';
		}

		$customerprofile->save();

		$userprofile = new Adposting;
		$userprofile->ad_id = $rand = mt_rand(1500, 5000);
		$userprofile->user_id = $request->user_id;
		$userprofile->subscription_id = $category_subscription_result[0]->id;
		$userprofile->category_id = $request->category_id;
		$userprofile->sub_category_id = $request->subcatid;
		$userprofile->formtype = $request->formtype;
		$userprofile->fullname = $request->fullname;
		$userprofile->email = $request->email;
		$userprofile->mobile = $request->mobile;
		$userprofile->is_mobile_hide = $hide_mobile;
		$userprofile->location = $request->ip();
		$userprofile->city = $request->city;
		$userprofile->price = $request->price;
		$userprofile->ad_title = $request->ad_title;
		$subscriptionRaw = Subscription::where('id', $category_subscription_result[0]->subscription_id)->where('delete_status', '0')->where('status', '0')->first();
		if ($subscriptionRaw->is_free == 'yes') {
			$userprofile->ad_type = 'Free';
		} else {
			$userprofile->ad_type = 'Paid';
		}
		//$userprofile->ad_expiry 		= $subscription_expiry;
		$userprofile->active_status = '0';
		$userprofile->ad_view_count = '0';
		$userprofile->ads_validity = $ads_validity;
		$userprofile->description = $request->description;
		$userprofile->subscription_id = $category_subscription_result[0]->id;
		$userprofile->delete_status = '0';
		$userprofile->status = '0';

		$ads_id = $userprofile->ad_id;

		if ($request->hasFile('file')) {
			$imageName = time() . '.' . $request->file->extension();
			$request->file->move(public_path('uploads/ads'), $imageName);
			$imagePath = public_path('uploads/ads/' . $imageName);
			ImageOptimizer::optimize($imagePath);
			$userprofile->image = url('public/uploads/ads') . '/' . $imageName;
			AdPostingImage::create([
				'ads_id' => $ads_id,
				'image' => url('public/uploads/ads') . '/' . $imageName,
				'image_no' => '1'
			]);

		} else {
			$userprofile->image = url('public/uploads/ads/dummy.jpeg');
		}
		$userprofile->save();

		$adimage = $userprofile->image;

		if ($request->hasFile('file1')) {
			$file = $request->file('file1');
			$name = time() . rand(1, 100) . '.' . $file->extension();
			$file->move(public_path('uploads/ads'), $name);
			$imagePath = public_path('uploads/ads/' . $name);
			ImageOptimizer::optimize($imagePath);
			AdPostingImage::create([
				'ads_id' => $ads_id,
				'image' => url('public/uploads/ads') . '/' . $name,
				'image_no' => '2'
			]);
		}

		if ($request->hasFile('file2')) {
			$file2 = $request->file('file2');
			$name2 = time() . rand(1, 100) . '.' . $file2->extension();
			$file2->move(public_path('uploads/ads'), $name2);
			$imagePath = public_path('uploads/ads/' . $name2);
			ImageOptimizer::optimize($imagePath);
			AdPostingImage::create([
				'ads_id' => $ads_id,
				'image' => url('public/uploads/ads') . '/' . $name2,
				'image_no' => '3'
			]);
		}
		if ($request->hasFile('file3')) {
			$file3 = $request->file('file3');
			$name3 = time() . rand(1, 100) . '.' . $file3->extension();
			$file3->move(public_path('uploads/ads'), $name3);
			$imagePath = public_path('uploads/ads/' . $name3);
			ImageOptimizer::optimize($imagePath);
			AdPostingImage::create([
				'ads_id' => $ads_id,
				'image' => url('public/uploads/ads') . '/' . $name3,
				'image_no' => '4'
			]);
		}

		if ($request->hasFile('file4')) {
			$file4 = $request->file('file4');
			$name4 = time() . rand(1, 100) . '.' . $file4->extension();
			$file4->move(public_path('uploads/ads'), $name4);
			$imagePath = public_path('uploads/ads/' . $name4);
			ImageOptimizer::optimize($imagePath);
			AdPostingImage::create([
				'ads_id' => $ads_id,
				'image' => url('public/uploads/ads') . '/' . $name4,
				'image_no' => '5'
			]);
		}

		$userprofile = new Vehicleform;

		$userprofile->image = $adimage;
		$userprofile->ads_id = $ads_id;
		$userprofile->user_id = $request->user_id;
		// 		$userprofile->subscription_id 	= $request->user_id;
		$userprofile->category_id = $request->category_id;
		$userprofile->sub_category_id = $request->subcatid;
		$userprofile->formtype = $request->formtype;
		$userprofile->fullname = $request->fullname;
		$userprofile->email = $request->email;
		$userprofile->mobile = $request->mobile;
		$userprofile->location = $request->ip();
		$userprofile->state = $request->state;
		$userprofile->state_name = $request->state_name;
		$userprofile->city = $request->city;
		$userprofile->city_name = $request->city_name;
		$userprofile->neibourhood = $request->neibourhood;
		$userprofile->price = $request->price;
		$userprofile->ad_title = $request->ad_title;
		if ($subscriptionRaw->is_free == 'yes') {
			$userprofile->ad_type = 'Free';
		} else {
			$userprofile->ad_type = 'Paid';
		}
		$userprofile->description = $request->description;
		$userprofile->brand = $request->brands;
		$userprofile->vehicle_type = $request->vehicle_type;
		$userprofile->fuel_type = $request->fuel_type;
		$userprofile->transmission = $request->transmission;
		$userprofile->year = $request->year;
		$userprofile->km = $request->km;
		$userprofile->delete_status = '0';
		$userprofile->status = '0';
		$userprofile->save();

		\Session::put('success', 'Post Added Successfully.');

		//return redirect("user-dashboard");
		return redirect("thank-you");
	}

	public function edit_post_vehicle_forms(Request $request)
	{

		$validator = Validator::make($request->all(), [
			'fullname' => 'required|max:50|min:0',
			'email' => 'required|max:50|min:0',
			'brands' => 'required|max:50|min:0',
			'vehicle_type' => 'required|max:50|min:0',
			'fuel_type' => 'required|max:50|min:0',
			'transmission' => 'required|max:50|min:0',
			'price' => 'required|max:50|min:0',
			'ad_title' => 'required|max:50|min:0',
			'description' => 'required|max:2500|min:0',
		]);

		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator)->withInput();
		}

		$subscription = '0';
		$category_id = $request->category_id;
		$user_id = $request->user_id;

		$ads_postings_count = DB::table('ads_postings')->where('user_id', $user_id)->where('ad_type', 'Free')->where('category_id', $category_id)->count('id');

		//$category_subscription_exists = DB::table('subscription_orders')->where('category_id',$category_id)->where('user_id',$user_id)->exists();

		$category_subscription_exists = DB::table("subscription_history")
			->where('status', '0')
			->where('user_id', $user_id)
			->exists();

		$category_id_exists = DB::table('subscriptions_free_trials')->where('category_id', $category_id)->exists();

		if ($category_id_exists) {
			$result_category_id = DB::table('subscriptions_free_trials')->where('category_id', $category_id)->get();
			$no_of_ads = $result_category_id[0]->no_of_ads;
			$ads_validity = $result_category_id[0]->ads_validity;
			// 		if($no_of_ads == $ads_postings_count  && $category_subscription_exists != 1)
			// 		{
			// 			\Session::put('success','No More Free Ads Purchase Subscription For More Posting.');
			// 			return redirect()->route('purchase-subscription');
			// 		}
		}

		if ($category_subscription_exists) {
			//	$category_subscription_result = DB::table('subscription_orders')->where('category_id',$category_id)->where('user_id',$user_id)->get();

			$category_subscription_result = DB::table("subscription_history")
				->where('user_id', $user_id)
				->where('status', '0')
				->orderBy('created_at', 'DESC')
				->get();

			$payment_status = $category_subscription_result[0]->payment_status;
			$used_ads = $category_subscription_result[0]->used_ads;
			$remaining_ads = $category_subscription_result[0]->remaining_ads;
			$status = $category_subscription_result[0]->status;

			// 		if($payment_status != 'Completed')
			// 		{
			// 			\Session::put('success','Payment Is Pending');
			// 			return redirect()->route('purchase-subscription');
			// 		}

			// 		if($used_ads == $remaining_ads)
			// 		{
			// 			\Session::put('success','All Ads Are Used.');
			// 			return redirect()->route('purchase-subscription');
			// 		}

			if ($status == '1') {
				\Session::put('success', 'Subscription Validity Expired');
				return redirect()->route('purchase-subscription');
			}
		}
		$no = $ads_validity;
		$dates = date("d-m-Y");
		$date = date_create($dates);

		date_add($date, date_interval_create_from_date_string($no . "days"));
		$subscription_expiry = date_format($date, "d-m-Y");

		$customerprofile = Customer::find($user_id);

		if ($request->file('changeprofile')) {
			$imageName = time() . '.' . $request->changeprofile->extension();
			$request->changeprofile->move(public_path('uploads/ads'), $imageName);
			$customerprofile->image = url('public/uploads/ads') . '/' . $imageName;

		}

		if ($request->file('userprofile')) {
			$imageName = time() . '.' . $request->userprofile->extension();
			$request->userprofile->move(public_path('uploads/ads'), $imageName);
			$customerprofile->image = url('public/uploads/ads') . '/' . $imageName;

		}

		$customerprofile->save();

		if (isset($request->is_mobile_hide)) {
			$hide_mobile = '1';
		} else {
			$hide_mobile = '0';
		}



		//$userprofile = Adposting::where('ad_id',$request->ads_id)->first();
		$userprofile = Adposting::find($request->ad_id);
		if (isset($request->mobile)) {
			$mobile_number = $request->mobile;
		} else {
			$mobile_number = $userprofile->mobile;
		}

		$userprofile->ad_id = $request->ads_id;
		$userprofile->user_id = $request->user_id;
		$userprofile->category_id = $request->category_id;
		$userprofile->sub_category_id = $request->subcatid;
		$userprofile->formtype = $request->formtype;
		$userprofile->fullname = $request->fullname;
		$userprofile->email = $request->email;
		$userprofile->mobile = $mobile_number;
		$userprofile->is_mobile_hide = $hide_mobile;
		$userprofile->location = $request->ip();
		$userprofile->city = $request->city;
		$userprofile->price = $request->price;
		$userprofile->ad_title = $request->ad_title;
		$subscriptionRaw = Subscription::where('id', $category_subscription_result[0]->subscription_id)->where('delete_status', '0')->where('status', '0')->first();
		if ($subscriptionRaw->is_free == 'yes') {
			$userprofile->ad_type = 'Free';
		} else {
			$userprofile->ad_type = 'Paid';
		}
		//$userprofile->ad_expiry 		= $subscription_expiry;
		$userprofile->active_status = '0';
		//	$userprofile->ad_view_count 	= '0';
		$userprofile->ads_validity = $ads_validity;
		$userprofile->description = $request->description;
		$userprofile->delete_status = '0';
		$userprofile->status = '0';

		$ads_id = $request->ads_id;

		if ($request->file('file')) {
			$imageName = time() . '.' . $request->file->extension();
			$request->file->move(public_path('uploads/ads'), $imageName);
			$userprofile->image = url('public/uploads/ads') . '/' . $imageName;
			$imagePath = public_path('uploads/ads/' . $imageName);
			ImageOptimizer::optimize($imagePath);
			AdPostingImage::updateOrCreate(['ads_id' => $ads_id, 'image_no' => '1'], [
				'ads_id' => $ads_id,
				'image_no' => '1',
				'image' => url('public/uploads/ads') . '/' . $imageName,
			]);

		}
		$userprofile->save();

		$adimage = $userprofile->image;

		if ($request->hasFile('file1')) {
			$file = $request->file('file1');
			$name = time() . rand(1, 100) . '.' . $file->extension();
			$file->move(public_path('uploads/ads'), $name);
			$imagePath = public_path('uploads/ads/' . $name);
			ImageOptimizer::optimize($imagePath);

			AdPostingImage::updateOrCreate(['ads_id' => $ads_id, 'image_no' => '2'], [
				'ads_id' => $ads_id,
				'image_no' => '2',
				'image' => url('public/uploads/ads') . '/' . $name,
			]);
		}

		if ($request->hasFile('file2')) {
			$file2 = $request->file('file2');
			$name2 = time() . rand(1, 100) . '.' . $file2->extension();
			$file2->move(public_path('uploads/ads'), $name2);
			$imagePath = public_path('uploads/ads/' . $name2);
			ImageOptimizer::optimize($imagePath);

			AdPostingImage::updateOrCreate(['ads_id' => $ads_id, 'image_no' => '3'], [
				'ads_id' => $ads_id,
				'image_no' => '3',
				'image' => url('public/uploads/ads') . '/' . $name2,
			]);
		}
		if ($request->hasFile('file3')) {
			$file3 = $request->file('file3');
			$name3 = time() . rand(1, 100) . '.' . $file3->extension();
			$file3->move(public_path('uploads/ads'), $name3);
			$imagePath = public_path('uploads/ads/' . $name3);
			ImageOptimizer::optimize($imagePath);

			AdPostingImage::updateOrCreate(['ads_id' => $ads_id, 'image_no' => '4'], [
				'ads_id' => $ads_id,
				'image_no' => '4',
				'image' => url('public/uploads/ads') . '/' . $name3,
			]);
		}

		if ($request->hasFile('file4')) {
			$file4 = $request->file('file4');
			$name4 = time() . rand(1, 100) . '.' . $file4->extension();
			$file4->move(public_path('uploads/ads'), $name4);
			$imagePath = public_path('uploads/ads/' . $name4);
			ImageOptimizer::optimize($imagePath);

			AdPostingImage::updateOrCreate(['ads_id' => $ads_id, 'image_no' => '5'], [
				'ads_id' => $ads_id,
				'image_no' => '5',
				'image' => url('public/uploads/ads') . '/' . $name4,
			]);
		}

		if ($request->hasFile('file4')) {
			$file4 = $request->file('file4');
			$name4 = time() . rand(1, 100) . '.' . $file4->extension();
			$file4->move(public_path('uploads/ads'), $name4);


			AdPostingImage::updateOrCreate(['ads_id' => $ads_id, 'image_no' => '5'], [
				'ads_id' => $ads_id,
				'image_no' => '5',
				'image' => url('public/uploads/ads') . '/' . $name4,
			]);
		}

		$userprofile = Vehicleform::where('ads_id', $request->ads_id)->first();

		$userprofile->image = $adimage;
		$userprofile->ads_id = $ads_id;
		$userprofile->user_id = $request->user_id;
		$userprofile->category_id = $request->category_id;
		$userprofile->sub_category_id = $request->subcatid;
		$userprofile->formtype = $request->formtype;
		$userprofile->fullname = $request->fullname;
		$userprofile->email = $request->email;
		$userprofile->mobile = $mobile_number;
		$userprofile->location = $request->ip();
		$userprofile->state = $request->state;
		$userprofile->state_name = $request->state_name;
		$userprofile->city = $request->city;
		$userprofile->city_name = $request->city_name;
		$userprofile->neibourhood = $request->neibourhood;
		$userprofile->price = $request->price;
		$userprofile->ad_title = $request->ad_title;
		if ($subscriptionRaw->is_free == 'yes') {
			$userprofile->ad_type = 'Free';
		} else {
			$userprofile->ad_type = 'Paid';
		}
		$userprofile->description = $request->description;
		$userprofile->brand = $request->brands;
		$userprofile->vehicle_type = $request->vehicle_type;
		$userprofile->fuel_type = $request->fuel_type;
		$userprofile->transmission = $request->transmission;
		$userprofile->year = $request->year;
		$userprofile->km = $request->km;
		$userprofile->delete_status = '0';
		$userprofile->status = '0';
		$userprofile->save();

		\Session::put('success', 'Post Updated Successfully.');

		//return redirect("user-dashboard");
		return redirect("my-ads");
	}

	public function thank_you(Request $request)
	{
		$data['PremiumCategories'] = Categories::where('delete_status', '0')->where('premium', '1')->orderby('id', 'desc')->get();
		$data['TopCategories'] = Categories::where('delete_status', '0')->where('top', '1')->orderby('id', 'desc')->get();
		$data['TrendingCategories'] = Categories::where('delete_status', '0')->where('trending', '1')->orderby('id', 'desc')->get();
		$data['allcategories'] = Categories::where('delete_status', '0')->orderby('name', 'ASC')->get();
		$data['Pages'] = Pages::where('delete_status', '0')->get();
		$data['city'] = City::where('delete_status', '0')->orderby('name', 'asc')->get();
		$data['countries'] = Countries::where('delete_status', '0')->orderby('id', 'desc')->get();
		$data['states'] = States::where('delete_status', '0')->orderby('id', 'desc')->get();
		$data['zip'] = Zip::where('delete_status', '0')->orderby('id', 'desc')->get();
		$userIp = $request->ip();
		$data['locationinfo'] = \Location::get($userIp);
		return view('website.thank-you', $data);
	}

	public function post_property_forms(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'property_type' => 'required',
			//  'bedroom'               => 'required',
			//   'bathroom'              => 'required',
			'residence_status' => 'required',
			'furnishing_status' => 'required',
			'construction_status' => 'required',
			'listed_by' => 'required',
			'plot_type' => 'required',
			'price_mention' => 'required',
			'car_parking' => 'required',
			'facing' => 'required',
			'project_name' => 'required',
			'file' => 'required|image|max:8192',   // 8MB max size
			'file1' => 'nullable|image|max:8192',   // 8MB max size, nullable
			'file2' => 'nullable|image|max:8192',   // 8MB max size, nullable
			'file3' => 'nullable|image|max:8192',   // 8MB max size, nullable
			'file4' => 'nullable|image|max:8192',   // 8MB max size, nullable
			'fullname' => 'required|max:50|min:0',
			'email' => 'required|max:50|min:0',
			'mobile' => 'required|max:50|min:0',
			'price' => 'required|max:50|min:0',
			'ad_title' => 'required|max:50|min:0',
			'description' => 'required|max:2500|min:0',
		]);

		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator)->withInput();
		}

		$subscription = '0';
		$category_id = $request->category_id;
		$user_id = $request->user_id;

		$ads_postings_count = DB::table('ads_postings')->where('user_id', $user_id)->where('delete_status', 0)->count('id');
		$active_postings_count = DB::table('ads_postings')->where('user_id', $user_id)->where('ad_type', 'Free')->where('active_status', '1')->count('id');
		$pending_ads_count = DB::table('ads_postings')
			->where('user_id', $user_id)
			->where('delete_status', 0)
			->where('active_status', '0')
			->count('id');

		//$category_subscription_exists = DB::table('subscription_orders')->where('category_id',$category_id)->where('user_id',$user_id)->exists();

		$category_subscription_exists = DB::table("subscription_history")
			->where('status', '0')
			->where('user_id', $user_id)
			->exists();
		$category_id_exists = DB::table('subscriptions_free_trials')->where('category_id', $category_id)->exists();

		//	if($category_id_exists)
		//	{
		$result_category_id = DB::table('subscriptions_free_trials')->where('category_id', $category_id)->get();
		$no_of_ads = $result_category_id[0]->no_of_ads;
		$ads_validity = $result_category_id[0]->ads_validity;

		//	if($no_of_ads == $ads_postings_count  && $category_subscription_exists != 1)
		if ($category_subscription_exists != 1) {
			if ($request->hasFile('file')) {
				$imageName = time() . '.' . $request->file->extension();
				$request->file->move(public_path('uploads/ads'), $imageName);
				$imagePath = public_path('uploads/ads/' . $imageName);
				ImageOptimizer::optimize($imagePath);
				$imageSession = url('public/uploads/ads') . '/' . $imageName;
			}

			if ($request->hasFile('file1')) {
				$file = $request->file('file1');
				$name = time() . rand(1, 100) . '.' . $file->extension();
				$file->move(public_path('uploads/ads'), $name);
				$imagePath = public_path('uploads/ads/' . $name);
				ImageOptimizer::optimize($imagePath);
				$imageSession2 = url('public/uploads/ads') . '/' . $name;
			}

			if ($request->hasFile('file2')) {
				$file2 = $request->file('file2');
				$name2 = time() . rand(1, 100) . '.' . $file2->extension();
				$file2->move(public_path('uploads/ads'), $name2);
				$imagePath = public_path('uploads/ads/' . $name2);
				ImageOptimizer::optimize($imagePath);
				$imageSession3 = url('public/uploads/ads') . '/' . $name2;
			}
			if ($request->hasFile('file3')) {
				$file3 = $request->file('file3');
				$name3 = time() . rand(1, 100) . '.' . $file3->extension();
				$file3->move(public_path('uploads/ads'), $name3);
				$imagePath = public_path('uploads/ads/' . $name3);
				ImageOptimizer::optimize($imagePath);
				$imageSession4 = url('public/uploads/ads') . '/' . $name3;
			}
			if ($request->hasFile('file4')) {
				$file4 = $request->file('file4');
				$name4 = time() . rand(1, 100) . '.' . $file4->extension();
				$file4->move(public_path('uploads/ads'), $name4);
				$imagePath = public_path('uploads/ads/' . $name4);
				ImageOptimizer::optimize($imagePath);
				$imageSession5 = url('public/uploads/ads') . '/' . $name4;
			}
			Session::put('ImageAd', ['image1' => $imageSession ?? null, 'image2' => $imageSession2 ?? null, 'image3' => $imageSession3 ?? null, 'image4' => $imageSession4 ?? null,]);

			Session::put('UserProfile', [
				'image' => $imageSession,
				'ads_validity' => $ads_validity,
				'ad_id' => $rand = mt_rand(1500, 5000),
				'category_id' => $request->category_id,
				'user_id' => $request->user_id,
				'sub_category_id' => $request->subcatid,
				'formtype' => $request->formtype,
				'fullname' => $request->fullname,
				'email' => $request->email,
				'mobile' => $request->mobile,
				'location' => $request->ip(),
				'city' => $request->city,
				'price' => $request->price,
				'ad_title' => $request->ad_title,
				'ad_type' => 'Free',
				'active_status' => '0',
				'ad_view_count' => '0',
				'description' => $request->description,
				'delete_status' => '0',
				'status' => '0'
			]);

			Session::put('PropertyDetail', [
				'user_id' => $request->user_id,
				'category_id' => $request->category_id,
				'sub_category_id' => $request->subcatid,
				'formtype' => $request->formtype,
				'fullname' => $request->fullname,
				'email' => $request->email,
				'mobile' => $request->mobile,
				'location' => $request->ip(),
				'state' => $request->state,
				'state_name' => $request->state_name,
				'city' => $request->city,
				'city_name' => $request->city_name,
				'neibourhood' => $request->neibourhood,
				'price' => $request->price,
				'ad_title' => $request->ad_title,
				'ad_type' => 'Free',
				'description' => $request->description,
				'property_type' => $request->property_type,
				'bedroom' => $request->bedroom,
				'bathroom' => $request->bathroom,
				'furnishing_status' => $request->furnishing_status,
				'construction_status' => $request->construction_status,
				'residence' => $request->residence_status,
				'listed_by' => $request->listed_by,
				'plot_type' => $request->plot_type,
				'price_mention' => $request->price_mention,
				'builtup_area' => $request->builtup_area,
				'carpet_area' => $request->carpet_area,
				'maintenance' => $request->maintenance,
				'total_floor' => $request->total_floor,
				'floor_no' => $request->floor_no,
				'car_parking' => $request->car_parking,
				'facing' => $request->facing,
				'project_name' => $request->project_name,
				'delete_status' => '0',
				'status' => '0'
			]);

			$event = DefaultNotification::where('event', 'free_limit')->first();
			if (!empty($event)) {
				$title = $event->title;
				$body = $event->content;
				$notifyArray = array(
					'user_id' => $user_id,
					'event_name' => $event->event,
					'title' => $title,
					'body' => $body,
				);

				$this->singleUserNotification($notifyArray);
			}
			\Session::put('success', 'No More Free Ads Purchase Subscription For More Posting.');
			return redirect()->route('purchase-subscription');
		}
		//}

		if ($category_subscription_exists) {
			//$category_subscription_result = DB::table('subscription_orders')->where('category_id',$category_id)->where('user_id',$user_id)->get();

			$category_subscription_result = DB::table("subscription_history")
				->where('user_id', $user_id)
				->where('status', '0')
				->orderBy('created_at', 'DESC')
				->get();

			$payment_status = $category_subscription_result[0]->payment_status;
			$used_ads = $category_subscription_result[0]->used_ads;
			$remaining_ads = $category_subscription_result[0]->remaining_ads;
			$status = $category_subscription_result[0]->status;

			if ($payment_status != 'Completed') {

				\Session::put('success', 'Payment Is Pending');
				return redirect()->route('purchase-subscription');
			}

			$usedAdsTotal = 0;
			$remainingAdsTotal = 0;

			foreach ($category_subscription_result as $subscription) {
				$usedAdsTotal += $subscription->used_ads;
				$remainingAdsTotal += $subscription->remaining_ads;
			}
			if ($ads_postings_count >= $remainingAdsTotal) {
				if ($request->hasFile('file')) {
					$imageName = time() . '.' . $request->file->extension();
					$request->file->move(public_path('uploads/ads'), $imageName);
					$imagePath = public_path('uploads/ads/' . $imageName);
					ImageOptimizer::optimize($imagePath);
					$imageSession = url('public/uploads/ads') . '/' . $imageName;
				}

				if ($request->hasFile('file1')) {
					$file = $request->file('file1');
					$name = time() . rand(1, 100) . '.' . $file->extension();
					$file->move(public_path('uploads/ads'), $name);
					$imagePath = public_path('uploads/ads/' . $name);
					ImageOptimizer::optimize($imagePath);
					$imageSession2 = url('public/uploads/ads') . '/' . $name;
				}

				if ($request->hasFile('file2')) {
					$file2 = $request->file('file2');
					$name2 = time() . rand(1, 100) . '.' . $file2->extension();
					$file2->move(public_path('uploads/ads'), $name2);
					$imagePath = public_path('uploads/ads/' . $name2);
					ImageOptimizer::optimize($imagePath);
					$imageSession3 = url('public/uploads/ads') . '/' . $name2;
				}
				if ($request->hasFile('file3')) {
					$file3 = $request->file('file3');
					$name3 = time() . rand(1, 100) . '.' . $file3->extension();
					$file3->move(public_path('uploads/ads'), $name3);
					$imagePath = public_path('uploads/ads/' . $name3);
					ImageOptimizer::optimize($imagePath);
					$imageSession4 = url('public/uploads/ads') . '/' . $name3;
				}
				if ($request->hasFile('file4')) {
					$file4 = $request->file('file4');
					$name4 = time() . rand(1, 100) . '.' . $file4->extension();
					$file4->move(public_path('uploads/ads'), $name4);
					$imagePath = public_path('uploads/ads/' . $name4);
					ImageOptimizer::optimize($imagePath);
					$imageSession5 = url('public/uploads/ads') . '/' . $name4;
				}

				Session::put('ImageAd', ['image1' => $imageSession ?? null, 'image2' => $imageSession2 ?? null, 'image3' => $imageSession3 ?? null, 'image4' => $imageSession4 ?? null,]);

				Session::put('UserProfile', [
					'image' => $imageSession,
					'ads_validity' => $ads_validity,
					'ad_id' => $rand = mt_rand(1500, 5000),
					'category_id' => $request->category_id,
					'user_id' => $request->user_id,
					'sub_category_id' => $request->subcatid,
					'formtype' => $request->formtype,
					'fullname' => $request->fullname,
					'email' => $request->email,
					'mobile' => $request->mobile,
					'location' => $request->ip(),
					'city' => $request->city,
					'price' => $request->price,
					'ad_title' => $request->ad_title,
					'ad_type' => 'Free',
					'active_status' => '0',
					'ad_view_count' => '0',
					'description' => $request->description,
					'delete_status' => '0',
					'status' => '0'
				]);

				Session::put('CommonDetail', [
					'user_id' => $request->user_id,
					'category_id' => $request->category_id,
					'sub_category_id' => $request->subcatid,
					'formtype' => $request->formtype,
					'fullname' => $request->fullname,
					'email' => $request->email,
					'mobile' => $request->mobile,
					'location' => $request->ip(),
					'state' => $request->state,
					'state_name' => $request->state_name,
					'city' => $request->city,
					'city_name' => $request->city_name,
					'neibourhood' => $request->neibourhood,
					'price' => $request->price,
					'ad_title' => $request->ad_title,
					'ad_type' => 'Free',
					'description' => $request->description
					,
					'delete_status' => '0',
					'status' => '0'
				]);
				\Session::put('success', 'All Ads Are Used in the bucket, please empty the bucket or buy new subscription.');
				return redirect()->route('purchase-subscription');
			}

			if ($status == '1') {
				Session::put('UserProfile', [
					'ads_validity' => $ads_validity,
					'ad_id' => $rand = mt_rand(1500, 5000),
					'category_id' => $request->category_id,
					'user_id' => $request->user_id,
					'sub_category_id' => $request->subcatid,
					'formtype' => $request->formtype,
					'fullname' => $request->fullname,
					'email' => $request->email,
					'mobile' => $request->mobile,
					'location' => $request->ip(),
					'city' => $request->city,
					'price' => $request->price,
					'ad_title' => $request->ad_title,
					'ad_type' => 'Free',
					'active_status' => '0',
					'ad_view_count' => '0',
					'description' => $request->description,
					'delete_status' => '0',
					'status' => '0'
				]);

				Session::put('PropertyDetail', [
					'user_id' => $request->user_id,
					'category_id' => $request->category_id,
					'sub_category_id' => $request->subcatid,
					'formtype' => $request->formtype,
					'fullname' => $request->fullname,
					'email' => $request->email,
					'mobile' => $request->mobile,
					'location' => $request->ip(),
					'state' => $request->state,
					'state_name' => $request->state_name,
					'city' => $request->city,
					'city_name' => $request->city_name,
					'neibourhood' => $request->neibourhood,
					'price' => $request->price,
					'ad_title' => $request->ad_title,
					'ad_type' => 'Free',
					'description' => $request->description,
					'property_type' => $request->property_type,
					'bedroom' => $request->bedroom,
					'bathroom' => $request->bathroom,
					'furnishing_status' => $request->furnishing_status,
					'construction_status' => $request->construction_status,
					'residence' => $request->residence_status,
					'listed_by' => $request->listed_by,
					'plot_type' => $request->plot_type,
					'price_mention' => $request->price_mention,
					'builtup_area' => $request->builtup_area,
					'carpet_area' => $request->carpet_area,
					'maintenance' => $request->maintenance,
					'total_floor' => $request->total_floor,
					'floor_no' => $request->floor_no,
					'car_parking' => $request->car_parking,
					'facing' => $request->facing,
					'project_name' => $request->project_name,
					'delete_status' => '0',
					'status' => '0'
				]);

				\Session::put('success', 'Subscription Validity Expired');
				return redirect()->route('purchase-subscription');
			}
		}

		$no = $ads_validity;
		$dates = date("d-m-Y");
		$date = date_create($dates);

		date_add($date, date_interval_create_from_date_string($no . "days"));
		$subscription_expiry = date_format($date, "d-m-Y");

		$customerprofile = Customer::find($user_id);

		if ($request->file('changeprofile')) {
			$imageName = time() . '.' . $request->changeprofile->extension();
			$request->changeprofile->move(public_path('uploads/ads'), $imageName);
			$customerprofile->image = url('public/uploads/ads') . '/' . $imageName;

		} else {
			$customerprofile->image = url('public/uploads/ads/dummy.jpeg');
		}

		if ($request->file('userprofile')) {
			$imageName = time() . '.' . $request->userprofile->extension();
			$request->userprofile->move(public_path('uploads/ads'), $imageName);
			$customerprofile->image = url('public/uploads/ads') . '/' . $imageName;

		} else {
			$customerprofile->image = url('public/uploads/ads/dummy.jpeg');
		}

		$customerprofile->save();

		if (isset($request->is_mobile_hide)) {
			$hide_mobile = '1';
		} else {
			$hide_mobile = '0';
		}

		$userprofile = new Adposting;

		$userprofile->ad_id = $rand = mt_rand(1500, 5000);
		$userprofile->user_id = $request->user_id;
		$userprofile->category_id = $request->category_id;
		$userprofile->subscription_id = $category_subscription_result[0]->id;
		$userprofile->sub_category_id = $request->subcatid;
		$userprofile->formtype = $request->formtype;
		$userprofile->fullname = $request->fullname;
		$userprofile->email = $request->email;
		$userprofile->mobile = $request->mobile;
		$userprofile->is_mobile_hide = $hide_mobile;
		$userprofile->location = $request->ip();
		$userprofile->city = $request->city;
		$userprofile->price = $request->price;
		$userprofile->ad_title = $request->ad_title;
		$subscriptionRaw = Subscription::where('id', $category_subscription_result[0]->subscription_id)->where('delete_status', '0')->where('status', '0')->first();
		if ($subscriptionRaw->is_free == 'yes') {
			$userprofile->ad_type = 'Free';
		} else {
			$userprofile->ad_type = 'Paid';
		}
		//$userprofile->ad_expiry  		= $subscription_expiry;
		$userprofile->active_status = '0';
		$userprofile->ad_view_count = '0';
		$userprofile->ads_validity = $ads_validity;
		$userprofile->description = $request->description;
		$userprofile->delete_status = '0';
		$userprofile->status = '0';
		$userprofile->save();

		$ads_id = $userprofile->ad_id;

		if ($request->hasFile('file')) {
			$imageName = time() . '.' . $request->file->extension();
			$request->file->move(public_path('uploads/ads'), $imageName);
			$imagePath = public_path('uploads/ads/' . $imageName);
			ImageOptimizer::optimize($imagePath);
			$userprofile->image = url('public/uploads/ads') . '/' . $imageName;
			AdPostingImage::create([
				'ads_id' => $ads_id,
				'image' => url('public/uploads/ads') . '/' . $imageName,
				'image_no' => '1'
			]);

		} else {
			$userprofile->image = url('public/uploads/ads/dummy.jpeg');
		}
		$userprofile->save();

		$adimage = $userprofile->image;

		if ($request->hasFile('file1')) {
			$file = $request->file('file1');
			$name = time() . rand(1, 100) . '.' . $file->extension();
			$file->move(public_path('uploads/ads'), $name);
			$imagePath = public_path('uploads/ads/' . $name);
			ImageOptimizer::optimize($imagePath);
			AdPostingImage::create([
				'ads_id' => $ads_id,
				'image' => url('public/uploads/ads') . '/' . $name,
				'image_no' => '2'
			]);
		}

		if ($request->hasFile('file2')) {
			$file2 = $request->file('file2');
			$name2 = time() . rand(1, 100) . '.' . $file2->extension();
			$file2->move(public_path('uploads/ads'), $name2);
			$imagePath = public_path('uploads/ads/' . $name2);
			ImageOptimizer::optimize($imagePath);
			AdPostingImage::create([
				'ads_id' => $ads_id,
				'image' => url('public/uploads/ads') . '/' . $name2,
				'image_no' => '3'
			]);
		}
		if ($request->hasFile('file3')) {
			$file3 = $request->file('file3');
			$name3 = time() . rand(1, 100) . '.' . $file3->extension();
			$file3->move(public_path('uploads/ads'), $name3);
			$imagePath = public_path('uploads/ads/' . $name3);
			ImageOptimizer::optimize($imagePath);
			AdPostingImage::create([
				'ads_id' => $ads_id,
				'image' => url('public/uploads/ads') . '/' . $name3,
				'image_no' => '4'
			]);
		}

		if ($request->hasFile('file4')) {
			$file4 = $request->file('file4');
			$name4 = time() . rand(1, 100) . '.' . $file4->extension();
			$file4->move(public_path('uploads/ads'), $name4);
			$imagePath = public_path('uploads/ads/' . $name4);
			ImageOptimizer::optimize($imagePath);
			AdPostingImage::create([
				'ads_id' => $ads_id,
				'image' => url('public/uploads/ads') . '/' . $name4,
				'image_no' => '5'
			]);
		}

		$userprofile = new Propertyform;

		$userprofile->image = $adimage;
		$userprofile->ads_id = $ads_id;
		$userprofile->user_id = $request->user_id;
		$userprofile->category_id = $request->category_id;
		$userprofile->sub_category_id = $request->subcatid;
		$userprofile->formtype = $request->formtype;
		$userprofile->fullname = $request->fullname;
		$userprofile->email = $request->email;
		$userprofile->mobile = $request->mobile;
		$userprofile->location = $request->ip();
		$userprofile->state = $request->state;
		$userprofile->state_name = $request->state_name;
		$userprofile->city = $request->city;
		$userprofile->city_name = $request->city_name;
		$userprofile->neibourhood = $request->neibourhood;
		$userprofile->price = $request->price;
		$userprofile->ad_title = $request->ad_title;
		if ($subscriptionRaw->is_free == 'yes') {
			$userprofile->ad_type = 'Free';
		} else {
			$userprofile->ad_type = 'Paid';
		}
		$userprofile->description = $request->description;
		$userprofile->property_type = $request->property_type;
		$userprofile->bedroom = $request->bedroom;
		$userprofile->bathroom = $request->bathroom;
		$userprofile->furnishing_status = $request->furnishing_status;
		$userprofile->construction_status = $request->construction_status;
		$userprofile->residence = $request->residence_status;
		$userprofile->listed_by = $request->listed_by;
		$userprofile->plot_type = $request->plot_type;
		$userprofile->price_mention = $request->price_mention;
		$userprofile->builtup_area = $request->builtup_area;
		$userprofile->carpet_area = $request->carpet_area;
		$userprofile->maintenance = $request->maintenance;
		$userprofile->total_floor = $request->total_floor;
		$userprofile->floor_no = $request->floor_no;
		$userprofile->car_parking = $request->car_parking;
		$userprofile->facing = $request->facing;
		$userprofile->project_name = $request->project_name;
		$userprofile->delete_status = '0';
		$userprofile->status = '0';
		$userprofile->save();

		// 		if($category_subscription_exists)
// 		{
// 			//$category_subscription_result = DB::table('subscription_orders')->where('category_id',$category_id)->where('user_id',$user_id)->get();
// 			$category_subscription_result = 	DB::table("subscription_history")
//             ->select("subscription_history.*")
//             ->whereRaw("find_in_set('".$category_id."',subscription_history.category_id)")
//             ->where('user_id',$user_id)
//             ->where('status','0')
//             ->get();

		// 			$used_ads 	= $category_subscription_result[0]->used_ads;
// 			$total_ads 	= $used_ads + 1;

		// 			DB::table("subscription_history")
//             ->select("subscription_history.*")
//             ->whereRaw("find_in_set('".$category_id."',subscription_history.category_id)")
//             ->where('user_id',$user_id)
//             ->where('status','0')
//             ->update(array('used_ads'=>$total_ads));

		// 			//DB::table('subscription_orders')->where('category_id',$category_id)->where('user_id',$user_id)->update(array('used_ads'=>$total_ads));
// 		}
		\Session::put('success', 'Post Added Successfully.');
		//return redirect("user-dashboard");
		return redirect("thank-you");
	}

	public function edit_post_property_forms(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'fullname' => 'required|max:50|min:0',
			'email' => 'required|max:50|min:0',
			//	'mobile'       =>'required|max:50|min:0',
			//'location'   =>'required|max:50|min:0',
			'price' => 'required|max:50|min:0',
			'ad_title' => 'required|max:50|min:0',
			'description' => 'required|max:2500|min:0',
		]);

		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator)->withInput();
		}

		$subscription = '0';
		$category_id = $request->category_id;
		$user_id = $request->user_id;

		$ads_postings_count = DB::table('ads_postings')->where('user_id', $user_id)->where('ad_type', 'Free')->where('category_id', $category_id)->count('id');

		//$category_subscription_exists = DB::table('subscription_orders')->where('category_id',$category_id)->where('user_id',$user_id)->exists();

		$category_subscription_exists = DB::table("subscription_history")
			->where('user_id', $user_id)
			->where('status', '0')
			->exists();

		$category_id_exists = DB::table('subscriptions_free_trials')->where('category_id', $category_id)->exists();

		if ($category_id_exists) {
			$result_category_id = DB::table('subscriptions_free_trials')->where('category_id', $category_id)->get();
			$no_of_ads = $result_category_id[0]->no_of_ads;
			$ads_validity = $result_category_id[0]->ads_validity;

			// 		if($no_of_ads == $ads_postings_count  && $category_subscription_exists != 1)
			// 		{
			// 			\Session::put('success','No More Free Ads Purchase Subscription For More Posting.');
			// 			return redirect()->route('purchase-subscription');
			// 		}
		}

		if ($category_subscription_exists) {
			//$category_subscription_result = DB::table('subscription_orders')->where('category_id',$category_id)->where('user_id',$user_id)->get();

			$category_subscription_result = DB::table("subscription_history")
				->where('user_id', $user_id)
				->where('status', '0')
				->orderBy('created_at', 'DESC')
				->get();

			$payment_status = $category_subscription_result[0]->payment_status;
			$used_ads = $category_subscription_result[0]->used_ads;
			$remaining_ads = $category_subscription_result[0]->remaining_ads;
			$status = $category_subscription_result[0]->status;

			// 		if($payment_status != 'Completed')
			// 		{
			// 			\Session::put('success','Payment Is Pending');
			// 			return redirect()->route('purchase-subscription');
			// 		}

			// 		if($used_ads == $remaining_ads)
			// 		{
			// 			\Session::put('success','All Ads Are Used.');
			// 			return redirect()->route('purchase-subscription');
			// 		}

			if ($status == '1') {
				\Session::put('success', 'Subscription Validity Expired');
				return redirect()->route('purchase-subscription');
			}
		}

		$no = $ads_validity;
		$dates = date("d-m-Y");
		$date = date_create($dates);

		date_add($date, date_interval_create_from_date_string($no . "days"));
		$subscription_expiry = date_format($date, "d-m-Y");

		$customerprofile = Customer::find($user_id);

		if ($request->file('changeprofile')) {
			$imageName = time() . '.' . $request->changeprofile->extension();
			$request->changeprofile->move(public_path('uploads/ads'), $imageName);
			$customerprofile->image = url('public/uploads/ads') . '/' . $imageName;

		} else {
			$customerprofile->image = url('public/uploads/ads/dummy.jpeg');
		}

		if ($request->file('userprofile')) {
			$imageName = time() . '.' . $request->userprofile->extension();
			$request->userprofile->move(public_path('uploads/ads'), $imageName);
			$customerprofile->image = url('public/uploads/ads') . '/' . $imageName;

		} else {
			$customerprofile->image = url('public/uploads/ads/dummy.jpeg');
		}

		$customerprofile->save();

		if (isset($request->is_mobile_hide)) {
			$hide_mobile = '1';
		} else {
			$hide_mobile = '0';
		}

		$userprofile = Adposting::find($request->ad_id);

		if (isset($request->mobile)) {
			$p_mobile = $request->mobile;
		} else {
			$p_mobile = $userprofile->mobile;
		}

		$userprofile->ad_id = $request->ads_id;
		$userprofile->user_id = $request->user_id;
		$userprofile->category_id = $request->category_id;
		$userprofile->sub_category_id = $request->subcatid;
		$userprofile->formtype = $request->formtype;
		$userprofile->fullname = $request->fullname;
		$userprofile->email = $request->email;
		$userprofile->mobile = $p_mobile;
		$userprofile->is_mobile_hide = $hide_mobile;
		$userprofile->location = $request->ip();
		$userprofile->city = $request->city;
		$userprofile->price = $request->price;
		$userprofile->ad_title = $request->ad_title;
		$subscriptionRaw = Subscription::where('id', $category_subscription_result[0]->subscription_id)->where('delete_status', '0')->where('status', '0')->first();
		if ($subscriptionRaw->is_free == 'yes') {
			$userprofile->ad_type = 'Free';
		} else {
			$userprofile->ad_type = 'Paid';
		}
		//$userprofile->ad_expiry  		= $subscription_expiry;
		$userprofile->active_status = '0';
		//	$userprofile->ad_view_count   = '0';
		$userprofile->ads_validity = $ads_validity;
		$userprofile->description = $request->description;
		$userprofile->delete_status = '0';
		$userprofile->status = '0';
		$userprofile->save();

		$ads_id = $request->ads_id;

		if ($request->file('file')) {
			$imageName = time() . '.' . $request->file->extension();
			$request->file->move(public_path('uploads/ads'), $imageName);
			$userprofile->image = url('public/uploads/ads') . '/' . $imageName;
			$imagePath = public_path('uploads/ads/' . $imageName);
			ImageOptimizer::optimize($imagePath);
			AdPostingImage::updateOrCreate(['ads_id' => $ads_id, 'image_no' => '1'], [
				'ads_id' => $ads_id,
				'image_no' => '1',
				'image' => url('public/uploads/ads') . '/' . $imageName,
			]);

		}
		$userprofile->save();

		$adimage = $userprofile->image;

		if ($request->hasFile('file1')) {
			$file = $request->file('file1');
			$name = time() . rand(1, 100) . '.' . $file->extension();
			$file->move(public_path('uploads/ads'), $name);
			$imagePath = public_path('uploads/ads/' . $name);
			ImageOptimizer::optimize($imagePath);

			AdPostingImage::updateOrCreate(['ads_id' => $ads_id, 'image_no' => '2'], [
				'ads_id' => $ads_id,
				'image_no' => '2',
				'image' => url('public/uploads/ads') . '/' . $name,
			]);
		}

		if ($request->hasFile('file2')) {
			$file2 = $request->file('file2');
			$name2 = time() . rand(1, 100) . '.' . $file2->extension();
			$file2->move(public_path('uploads/ads'), $name2);
			$imagePath = public_path('uploads/ads/' . $name2);
			ImageOptimizer::optimize($imagePath);

			AdPostingImage::updateOrCreate(['ads_id' => $ads_id, 'image_no' => '3'], [
				'ads_id' => $ads_id,
				'image_no' => '3',
				'image' => url('public/uploads/ads') . '/' . $name2,
			]);
		}
		if ($request->hasFile('file3')) {
			$file3 = $request->file('file3');
			$name3 = time() . rand(1, 100) . '.' . $file3->extension();
			$file3->move(public_path('uploads/ads'), $name3);
			$imagePath = public_path('uploads/ads/' . $name3);
			ImageOptimizer::optimize($imagePath);

			AdPostingImage::updateOrCreate(['ads_id' => $ads_id, 'image_no' => '4'], [
				'ads_id' => $ads_id,
				'image_no' => '4',
				'image' => url('public/uploads/ads') . '/' . $name3,
			]);
		}

		if ($request->hasFile('file4')) {
			$file4 = $request->file('file4');
			$name4 = time() . rand(1, 100) . '.' . $file4->extension();
			$file4->move(public_path('uploads/ads'), $name4);
			$imagePath = public_path('uploads/ads/' . $name4);
			ImageOptimizer::optimize($imagePath);

			AdPostingImage::updateOrCreate(['ads_id' => $ads_id, 'image_no' => '5'], [
				'ads_id' => $ads_id,
				'image_no' => '5',
				'image' => url('public/uploads/ads') . '/' . $name4,
			]);
		}

		$userprofile = Propertyform::where('ads_id', $request->ads_id)->first();

		$userprofile->image = $adimage;
		$userprofile->ads_id = $ads_id;
		$userprofile->user_id = $request->user_id;
		$userprofile->category_id = $request->category_id;
		$userprofile->sub_category_id = $request->subcatid;
		$userprofile->formtype = $request->formtype;
		$userprofile->fullname = $request->fullname;
		$userprofile->email = $request->email;
		$userprofile->mobile = $request->mobile;
		$userprofile->location = $request->ip();
		$userprofile->state = $request->state;
		$userprofile->state_name = $request->state_name;
		$userprofile->city = $request->city;
		$userprofile->city_name = $request->city_name;
		$userprofile->neibourhood = $request->neibourhood;
		$userprofile->price = $request->price;
		$userprofile->ad_title = $request->ad_title;
		if ($subscriptionRaw->is_free == 'yes') {
			$userprofile->ad_type = 'Free';
		} else {
			$userprofile->ad_type = 'Paid';
		}
		$userprofile->description = $request->description;
		$userprofile->property_type = $request->property_type;
		$userprofile->bedroom = $request->bedroom;
		$userprofile->bathroom = $request->bathroom;
		$userprofile->furnishing_status = $request->furnishing_status;
		$userprofile->construction_status = $request->construction_status;
		$userprofile->residence = $request->residence_status;
		$userprofile->listed_by = $request->listed_by;
		$userprofile->builtup_area = $request->builtup_area;
		$userprofile->carpet_area = $request->carpet_area;
		$userprofile->maintenance = $request->maintenance;
		$userprofile->total_floor = $request->total_floor;
		$userprofile->floor_no = $request->floor_no;
		$userprofile->car_parking = $request->car_parking;
		$userprofile->facing = $request->facing;
		$userprofile->project_name = $request->project_name;
		$userprofile->delete_status = '0';
		$userprofile->status = '0';
		$userprofile->save();
		\Session::put('success', 'Post Updated Successfully.');
		//return redirect("user-dashboard");
		return redirect("thank-you");
	}

	public function post_common_forms(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'fullname' => 'required|max:50',
			'email' => 'required|email|max:50',
			'mobile' => 'required|max:50',
			//'location'   =>'required|max:50', // If location is not required, you can remove it
			'price' => 'required|numeric|min:0', // Adjust the minimum value as needed
			'ad_title' => 'required|max:50',
			'description' => 'required|max:2500',
			'file' => 'required|image|max:8192',   // 8MB max size
			'file1' => 'nullable|image|max:8192',   // 8MB max size, nullable
			'file2' => 'nullable|image|max:8192',   // 8MB max size, nullable
			'file3' => 'nullable|image|max:8192',   // 8MB max size, nullable
			'file4' => 'nullable|image|max:8192',   // 8MB max size, nullable
		]);

		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator)->withInput();
		}

		$subscription = '0';
		$category_id = $request->category_id;
		$user_id = $request->user_id;

		//$category_subscription_exists = DB::table('subscription_orders')->where('category_id',$category_id)->where('user_id',$user_id)->exists();

		$category_subscription_exists = DB::table("subscription_history")
			->where('user_id', $user_id)
			->where('status', '0')
			->exists();

		$category_id_exists = DB::table('subscriptions_free_trials')->where('category_id', $category_id)->exists();

		//	if($category_id_exists)
		//	{
		$result_category_id = DB::table('subscriptions_free_trials')->where('category_id', $category_id)->get();

		// 			if(!isset($result_category_id))
// 			{
// 			    Session::put('UserProfile', ['ads_validity'=>$ads_validity,'ad_id'=>$rand = mt_rand(1500, 5000),'category_id' => $request->category_id,'user_id'=>$request->user_id,'sub_category_id'=>$request->subcatid,'formtype'=>$request->formtype,'fullname'=>$request->fullname,
//     		     'email'=>$request->email,'mobile'=>$request->mobile,'location'=>$request->location,'city'=>$request->city,'price'=>$request->price,'ad_title'=>$request->ad_title,
//     		     'ad_type'=>'Free','active_status'=>'0','ad_view_count'=>'0','description'=>$request->description,'delete_status'=>'0','status'=>'0']);

		//     			Session::put('CommonDetail',['user_id'=>$request->user_id,'category_id'=>$request->category_id,'sub_category_id'=>$request->subcatid,'formtype'=>$request->formtype,
//     			'fullname'=>$request->fullname,'email'=>$request->email,'mobile'=>$request->mobile,'location'=>$request->location,'state'=>$request->state,'state_name'=>$request->state_name,'city'=>$request->city,
//     			'city_name'=>$request->city_name,'neibourhood'=>$request->neibourhood,'price'=>$request->price,'ad_title'=>$request->ad_title,'ad_type'=>'Free','description'=>$request->description
//     			,'delete_status'=>'0','status'=>'0']);

		// 				\Session::put('success','No More Free Ads Purchase Subscription For More Posting.');
// 				return redirect()->route('purchase-subscription');
// 			}
		$ads_validity = $result_category_id[0]->ads_validity;
		$no_of_ads = $result_category_id[0]->no_of_ads;
		$active_postings_count = DB::table('ads_postings')->where('user_id', $user_id)->where('ad_type', 'Free')->where('active_status', '1')->count('id');
		$ads_postings_count = DB::table('ads_postings')
			->where('user_id', $user_id)
			->where('delete_status', 0)
			->count('id');

		//	if($no_of_ads == $ads_postings_count  && $category_subscription_exists != 1)
		if (!$category_subscription_exists) {
			if ($request->hasFile('file')) {
				$imageName = time() . '.' . $request->file->extension();
				$request->file->move(public_path('uploads/ads'), $imageName);
				$imagePath = public_path('uploads/ads/' . $imageName);
				ImageOptimizer::optimize($imagePath);
				$imageSession = url('public/uploads/ads') . '/' . $imageName;
			}

			if ($request->hasFile('file1')) {
				$file = $request->file('file1');
				$name = time() . rand(1, 100) . '.' . $file->extension();
				$file->move(public_path('uploads/ads'), $name);
				$imagePath = public_path('uploads/ads/' . $name);
				ImageOptimizer::optimize($imagePath);
				$imageSession2 = url('public/uploads/ads') . '/' . $name;
			}

			if ($request->hasFile('file2')) {
				$file2 = $request->file('file2');
				$name2 = time() . rand(1, 100) . '.' . $file2->extension();
				$file2->move(public_path('uploads/ads'), $name2);
				$imagePath = public_path('uploads/ads/' . $name2);
				ImageOptimizer::optimize($imagePath);
				$imageSession3 = url('public/uploads/ads') . '/' . $name2;
			}
			if ($request->hasFile('file3')) {
				$file3 = $request->file('file3');
				$name3 = time() . rand(1, 100) . '.' . $file3->extension();
				$file3->move(public_path('uploads/ads'), $name3);
				$imagePath = public_path('uploads/ads/' . $name3);
				ImageOptimizer::optimize($imagePath);
				$imageSession4 = url('public/uploads/ads') . '/' . $name3;
			}
			if ($request->hasFile('file4')) {
				$file4 = $request->file('file4');
				$name4 = time() . rand(1, 100) . '.' . $file4->extension();
				$file4->move(public_path('uploads/ads'), $name4);
				$imagePath = public_path('uploads/ads/' . $name4);
				ImageOptimizer::optimize($imagePath);
				$imageSession5 = url('public/uploads/ads') . '/' . $name4;
			}



			Session::put('ImageAd', ['image1' => $imageSession ?? null, 'image2' => $imageSession2 ?? null, 'image3' => $imageSession3 ?? null, 'image4' => $imageSession4 ?? null,]);

			Session::put('UserProfile', [
				'image' => $imageSession,
				'ads_validity' => $ads_validity,
				'ad_id' => $rand = mt_rand(1500, 5000),
				'category_id' => $request->category_id,
				'user_id' => $request->user_id,
				'sub_category_id' => $request->subcatid,
				'formtype' => $request->formtype,
				'fullname' => $request->fullname,
				'email' => $request->email,
				'mobile' => $request->mobile,
				'location' => $request->ip(),
				'city' => $request->city,
				'price' => $request->price,
				'ad_title' => $request->ad_title,
				'ad_type' => 'Free',
				'active_status' => '0',
				'ad_view_count' => '0',
				'description' => $request->description,
				'delete_status' => '0',
				'status' => '0'
			]);

			Session::put('CommonDetail', [
				'user_id' => $request->user_id,
				'category_id' => $request->category_id,
				'sub_category_id' => $request->subcatid,
				'formtype' => $request->formtype,
				'fullname' => $request->fullname,
				'email' => $request->email,
				'mobile' => $request->mobile,
				'location' => $request->ip(),
				'state' => $request->state,
				'state_name' => $request->state_name,
				'city' => $request->city,
				'city_name' => $request->city_name,
				'neibourhood' => $request->neibourhood,
				'price' => $request->price,
				'ad_title' => $request->ad_title,
				'ad_type' => 'Free',
				'description' => $request->description
				,
				'delete_status' => '0',
				'status' => '0'
			]);

			\Session::put('success', 'No Active Subscription found! Purchase Subscription For Posting.');
			return redirect()->route('purchase-subscription');
		}
		//	}

		if ($category_subscription_exists) {
			//$category_subscription_result = DB::table('subscription_orders')->where('category_id',$category_id)->where('user_id',$user_id)->get();

			$category_subscription_result = DB::table("subscription_history")
				->where('user_id', $user_id)
				->where('status', '0')
				->orderBy('created_at', 'DESC')
				->get();

			$payment_status = $category_subscription_result[0]->payment_status;
			$usedAdsTotal = 0;
			$remainingAdsTotal = 0;



			foreach ($category_subscription_result as $subscription) {
				$usedAdsTotal += $subscription->used_ads;
				$remainingAdsTotal += $subscription->remaining_ads;
			}
			if ($ads_postings_count >= $remainingAdsTotal) {
				if ($request->hasFile('file')) {
					$imageName = time() . '.' . $request->file->extension();
					$request->file->move(public_path('uploads/ads'), $imageName);
					$imagePath = public_path('uploads/ads/' . $imageName);
					ImageOptimizer::optimize($imagePath);
					$imageSession = url('public/uploads/ads') . '/' . $imageName;
				}

				if ($request->hasFile('file1')) {
					$file = $request->file('file1');
					$name = time() . rand(1, 100) . '.' . $file->extension();
					$file->move(public_path('uploads/ads'), $name);
					$imagePath = public_path('uploads/ads/' . $name);
					ImageOptimizer::optimize($imagePath);
					$imageSession2 = url('public/uploads/ads') . '/' . $name;
				}

				if ($request->hasFile('file2')) {
					$file2 = $request->file('file2');
					$name2 = time() . rand(1, 100) . '.' . $file2->extension();
					$file2->move(public_path('uploads/ads'), $name2);
					$imagePath = public_path('uploads/ads/' . $name2);
					ImageOptimizer::optimize($imagePath);
					$imageSession3 = url('public/uploads/ads') . '/' . $name2;
				}
				if ($request->hasFile('file3')) {
					$file3 = $request->file('file3');
					$name3 = time() . rand(1, 100) . '.' . $file3->extension();
					$file3->move(public_path('uploads/ads'), $name3);
					$imagePath = public_path('uploads/ads/' . $name3);
					ImageOptimizer::optimize($imagePath);
					$imageSession4 = url('public/uploads/ads') . '/' . $name3;
				}
				if ($request->hasFile('file4')) {
					$file4 = $request->file('file4');
					$name4 = time() . rand(1, 100) . '.' . $file4->extension();
					$file4->move(public_path('uploads/ads'), $name4);
					$imagePath = public_path('uploads/ads/' . $name4);
					ImageOptimizer::optimize($imagePath);
					$imageSession5 = url('public/uploads/ads') . '/' . $name4;
				}




				Session::put('ImageAd', ['image1' => $imageSession ?? null, 'image2' => $imageSession2 ?? null, 'image3' => $imageSession3 ?? null, 'image4' => $imageSession4 ?? null,]);

				Session::put('UserProfile', [
					'image' => $imageSession,
					'ads_validity' => $ads_validity,
					'ad_id' => $rand = mt_rand(1500, 5000),
					'category_id' => $request->category_id,
					'user_id' => $request->user_id,
					'sub_category_id' => $request->subcatid,
					'formtype' => $request->formtype,
					'fullname' => $request->fullname,
					'email' => $request->email,
					'mobile' => $request->mobile,
					'location' => $request->ip(),
					'city' => $request->city,
					'price' => $request->price,
					'ad_title' => $request->ad_title,
					'ad_type' => 'Free',
					'active_status' => '0',
					'ad_view_count' => '0',
					'description' => $request->description,
					'delete_status' => '0',
					'status' => '0'
				]);

				Session::put('CommonDetail', [
					'user_id' => $request->user_id,
					'category_id' => $request->category_id,
					'sub_category_id' => $request->subcatid,
					'formtype' => $request->formtype,
					'fullname' => $request->fullname,
					'email' => $request->email,
					'mobile' => $request->mobile,
					'location' => $request->ip(),
					'state' => $request->state,
					'state_name' => $request->state_name,
					'city' => $request->city,
					'city_name' => $request->city_name,
					'neibourhood' => $request->neibourhood,
					'price' => $request->price,
					'ad_title' => $request->ad_title,
					'ad_type' => 'Free',
					'description' => $request->description
					,
					'delete_status' => '0',
					'status' => '0'
				]);
				\Session::put('success', 'All Ads Are Used in the bucket, please empty the bucket or buy new subscription.');
				return redirect()->route('purchase-subscription');
			}

			$status = $category_subscription_result[0]->status;

			if ($payment_status != 'Completed') {

				\Session::put('success', 'Payment Is Pending');
				return redirect()->route('purchase-subscription');
			}

			if ($status == '1') {

				\Session::put('success', 'Subscription Validity Expired');
				return redirect()->route('purchase-subscription');
			}
		}

		$no = $ads_validity;
		$dates = date("d-m-Y");
		$date = date_create($dates);
		date_add($date, date_interval_create_from_date_string($no . "days"));
		$subscription_expiry = date_format($date, "d-m-Y");

		$customerprofile = Customer::find($user_id);
		$subscriptionRaw = Subscription::where('id', $category_subscription_result[0]->subscription_id)->where('delete_status', '0')->where('status', '0')->first();

		if ($request->file('changeprofile')) {
			$imageName = time() . '.' . $request->changeprofile->extension();
			$request->changeprofile->move(public_path('uploads/ads'), $imageName);
			$customerprofile->image = url('public/uploads/ads') . '/' . $imageName;

		} else {
			$customerprofile->image = url('public/uploads/ads/dummy.jpeg');
		}

		if ($request->file('userprofile')) {
			$imageName = time() . '.' . $request->userprofile->extension();
			$request->userprofile->move(public_path('uploads/ads'), $imageName);
			$customerprofile->image = url('public/uploads/ads') . '/' . $imageName;


		} else {
			$customerprofile->image = url('public/uploads/ads/dummy.jpeg');
		}

		$customerprofile->save();

		if (isset($request->is_mobile_hide)) {
			$hide_mobile = '1';
		} else {
			$hide_mobile = '0';
		}

		$userprofile = new Adposting;

		$userprofile->ad_id = $rand = mt_rand(1500, 5000);
		$userprofile->user_id = $request->user_id;
		$userprofile->subscription_id = $category_subscription_result[0]->id;
		$userprofile->category_id = $request->category_id;
		$userprofile->sub_category_id = $request->subcatid;
		$userprofile->formtype = $request->formtype;
		$userprofile->fullname = $request->fullname;
		$userprofile->email = $request->email;
		$userprofile->mobile = $request->mobile;
		$userprofile->is_mobile_hide = $hide_mobile;
		$userprofile->location = $request->ip();
		$userprofile->city = $request->city;
		$userprofile->price = $request->price;
		$userprofile->ad_title = $request->ad_title;
		if ($subscriptionRaw->is_free == 'yes') {
			$userprofile->ad_type = 'Free';
		} else {
			$userprofile->ad_type = 'Paid';
		}
		//$userprofile->ad_expiry    	= $subscription_expiry;
		$userprofile->active_status = '0';
		$userprofile->ad_view_count = '0';
		$userprofile->ads_validity = $ads_validity;
		$userprofile->description = $request->description;
		$userprofile->delete_status = '0';
		$userprofile->status = '0';
		//	$userprofile->save();

		$ads_id = $userprofile->ad_id;

		if ($request->hasFile('file')) {
			$imageName = time() . '.' . $request->file->extension();
			$request->file->move(public_path('uploads/ads'), $imageName);
			$imagePath = public_path('uploads/ads/' . $imageName);
			ImageOptimizer::optimize($imagePath);
			$userprofile->image = url('public/uploads/ads') . '/' . $imageName;
			AdPostingImage::create([
				'ads_id' => $ads_id,
				'image' => url('public/uploads/ads') . '/' . $imageName,
				'image_no' => '1'
			]);

		} else {
			$userprofile->image = url('public/uploads/ads/dummy.jpeg');
		}
		$userprofile->save();

		$adimage = $userprofile->image;

		if ($request->hasFile('file1')) {
			$file = $request->file('file1');
			$name = time() . rand(1, 100) . '.' . $file->extension();
			$file->move(public_path('uploads/ads'), $name);
			$imagePath = public_path('uploads/ads/' . $name);
			ImageOptimizer::optimize($imagePath);
			AdPostingImage::create([
				'ads_id' => $ads_id,
				'image' => url('public/uploads/ads') . '/' . $name,
				'image_no' => '2'
			]);
		}

		if ($request->hasFile('file2')) {
			$file2 = $request->file('file2');
			$name2 = time() . rand(1, 100) . '.' . $file2->extension();
			$file2->move(public_path('uploads/ads'), $name2);
			$imagePath = public_path('uploads/ads/' . $name2);
			ImageOptimizer::optimize($imagePath);
			AdPostingImage::create([
				'ads_id' => $ads_id,
				'image' => url('public/uploads/ads') . '/' . $name2,
				'image_no' => '3'
			]);
		}
		if ($request->hasFile('file3')) {
			$file3 = $request->file('file3');
			$name3 = time() . rand(1, 100) . '.' . $file3->extension();
			$file3->move(public_path('uploads/ads'), $name3);
			$imagePath = public_path('uploads/ads/' . $name3);
			ImageOptimizer::optimize($imagePath);
			AdPostingImage::create([
				'ads_id' => $ads_id,
				'image' => url('public/uploads/ads') . '/' . $name3,
				'image_no' => '4'
			]);
		}

		if ($request->hasFile('file4')) {
			$file4 = $request->file('file4');
			$name4 = time() . rand(1, 100) . '.' . $file4->extension();
			$file4->move(public_path('uploads/ads'), $name4);
			$imagePath = public_path('uploads/ads/' . $name4);
			ImageOptimizer::optimize($imagePath);
			AdPostingImage::create([
				'ads_id' => $ads_id,
				'image' => url('public/uploads/ads') . '/' . $name4,
				'image_no' => '5'
			]);
		}

		$userprofile = new Commonform;

		$userprofile->image = $adimage;
		$userprofile->ads_id = $ads_id;
		$userprofile->user_id = $request->user_id;
		$userprofile->category_id = $request->category_id;
		$userprofile->sub_category_id = $request->subcatid;
		$userprofile->formtype = $request->formtype;
		$userprofile->fullname = $request->fullname;
		$userprofile->email = $request->email;
		$userprofile->mobile = $request->mobile;
		$userprofile->location = $request->ip();
		$userprofile->state = $request->state;
		$userprofile->state_name = $request->state_name;
		$userprofile->city = $request->city;
		$userprofile->city_name = $request->city_name;
		$userprofile->neibourhood = $request->neibourhood;
		$userprofile->price = $request->price;
		$userprofile->ad_title = $request->ad_title;
		if ($subscriptionRaw->is_free == 'yes') {
			$userprofile->ad_type = 'Free';
		} else {
			$userprofile->ad_type = 'Paid';
		}
		$userprofile->description = $request->description;
		$userprofile->delete_status = '0';
		$userprofile->status = '0';
		$userprofile->save();


		/****************New Added Admin email *****/

		$adEmailDetail = array(
			'ad_id' => $userprofile->ad_id,
			'fullname' => $userprofile->fullname,
			'email' => $userprofile->email,
			'mobile' => $userprofile->mobile,
			'description' => $userprofile->description,
			'ad_title' => $userprofile->ad_title,
			'price' => $userprofile->price ?? "",
			'city' => $userprofile->city_name ?? ""
		);
		$messagead = '';
		Mail::send('email.new-ad-post', $adEmailDetail, function ($messagead) use ($adEmailDetail, $customerprofile) {
			$messagead->to('choudharyfaizasif@gmail.com', 'Welcome Post')->subject('New Ad Request by Publisher ID ' . $customerprofile->member_id);
			$messagead->from($adEmailDetail['email'], $adEmailDetail['fullname']);
		});


		\Session::put('success', 'Post Added Successfully.');
		//return redirect("user-dashboard");
		return redirect("thank-you");
	}

	public function edit_post_common_forms(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'fullname' => 'required|max:50|min:0',
			'email' => 'required|max:50|min:0',
			'price' => 'required|max:50|min:0',
			'ad_title' => 'required|max:50|min:0',
			'description' => 'required|max:2500|min:0',
		]);

		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator)->withInput();
		}

		$subscription = '0';
		$category_id = $request->category_id;
		$user_id = $request->user_id;

		$ads_postings_count = DB::table('ads_postings')->where('user_id', $user_id)->where('ad_type', 'Free')->where('category_id', $category_id)->count('id');

		//$category_subscription_exists = DB::table('subscription_orders')->where('category_id',$category_id)->where('user_id',$user_id)->exists();

		$category_subscription_exists = DB::table("subscription_history")
			->where('user_id', $user_id)
			->where('status', '0')
			->exists();

		$category_id_exists = DB::table('subscriptions_free_trials')->where('category_id', $category_id)->exists();

		if ($category_id_exists) {
			$result_category_id = DB::table('subscriptions_free_trials')->where('category_id', $category_id)->get();
			$no_of_ads = $result_category_id[0]->no_of_ads;
			$ads_validity = $result_category_id[0]->ads_validity;

			// 			if($no_of_ads == $ads_postings_count  && $category_subscription_exists != 1)
// 			{
// 				\Session::put('success','No More Free Ads Purchase Subscription For More Posting.');
// 				return redirect()->route('purchase-subscription');
// 			}
		}

		if ($category_subscription_exists) {
			$category_subscription_result = DB::table("subscription_history")
				->where('user_id', $user_id)
				->where('status', '0')
				->get();

			$payment_status = $category_subscription_result[0]->payment_status;
			$used_ads = $category_subscription_result[0]->used_ads;
			$remaining_ads = $category_subscription_result[0]->remaining_ads;
			$status = $category_subscription_result[0]->status;

			// 			if($payment_status != 'Completed')
// 			{
// 				\Session::put('success','Payment Is Pending');
// 				return redirect()->route('purchase-subscription');
// 			}

			// 			if($used_ads == $remaining_ads)
// 			{
// 				\Session::put('success','All Ads Are Used.');
// 				return redirect()->route('purchase-subscription');
// 			}
			if ($status == '1') {
				\Session::put('success', 'Subscription Validity Expired');
				return redirect()->route('purchase-subscription');
			}
		}

		$no = $ads_validity;
		$dates = date("d-m-Y");
		$date = date_create($dates);
		date_add($date, date_interval_create_from_date_string($no . "days"));
		$subscription_expiry = date_format($date, "d-m-Y");

		$customerprofile = Customer::find($user_id);

		if ($request->hasFile('changeprofile')) {
			$imageName = time() . '.' . $request->changeprofile->extension();
			$request->changeprofile->move(public_path('uploads/ads'), $imageName);
			$customerprofile->image = url('public/uploads/ads') . '/' . $imageName;
		} else {
			$customerprofile->image = url('public/uploads/ads/dummy.jpeg');
		}

		if ($request->hasFile('userprofile')) {
			$imageName = time() . '.' . $request->userprofile->extension();
			$request->userprofile->move(public_path('uploads/ads'), $imageName);
			$customerprofile->image = url('public/uploads/ads') . '/' . $imageName;

		} else {
			$customerprofile->image = url('public/uploads/ads/dummy.jpeg');
		}

		$customerprofile->save();

		if (isset($request->is_mobile_hide)) {
			$hide_mobile = '1';
		} else {
			$hide_mobile = '0';
		}

		$userprofile = Adposting::find($request->ad_id);
		if (isset($request->mobile)) {
			$mobile_number = $request->mobile;
		} else {
			$mobile_number = $userprofile->mobile;
		}

		$userprofile->ad_id = $request->ads_id;
		$userprofile->user_id = $request->user_id;
		$userprofile->category_id = $request->category_id;
		$userprofile->sub_category_id = $request->subcatid;
		$userprofile->formtype = $request->formtype;
		$userprofile->fullname = $request->fullname;
		$userprofile->email = $request->email;
		$userprofile->mobile = $mobile_number;
		//	$userprofile->mobile        	= $request->mobile;
		$userprofile->is_mobile_hide = $hide_mobile;
		$userprofile->location = $request->ip();
		$userprofile->city = $request->city;
		$userprofile->price = $request->price;
		$userprofile->ad_title = $request->ad_title;
		$subscriptionRaw = Subscription::where('id', $category_subscription_result[0]->subscription_id)->where('delete_status', '0')->where('status', '0')->first();
		if ($subscriptionRaw->is_free == 'yes') {
			$userprofile->ad_type = 'Free';
		} else {
			$userprofile->ad_type = 'Paid';
		}
		//$userprofile->ad_expiry    	= $subscription_expiry;
		$userprofile->active_status = '0';
		//$userprofile->ad_view_count   = '0';
		$userprofile->ads_validity = $ads_validity;
		$userprofile->description = $request->description;
		$userprofile->delete_status = '0';
		$userprofile->status = '0';
		//$userprofile->save();

		$ads_id = $request->ads_id;

		if ($request->hasFile('file1')) {
			$file = $request->file('file1');
			$name = time() . rand(1, 100) . '.' . $file->extension();
			$file->move(public_path('uploads/ads'), $name);
			$imagePath = public_path('uploads/ads/' . $name);
			ImageOptimizer::optimize($imagePath);
			$userprofile->image = url('public/uploads/ads') . '/' . $name;

			AdPostingImage::updateOrCreate(['ads_id' => $ads_id, 'image_no' => '2'], [
				'ads_id' => $ads_id,
				'image_no' => '2',
				'image' => url('public/uploads/ads') . '/' . $name,
			]);
		}

		$userprofile->save();

		$adimage = $userprofile->image;

		if ($request->hasFile('file2')) {
			$file2 = $request->file('file2');
			$name2 = time() . rand(1, 100) . '.' . $file2->extension();
			$file2->move(public_path('uploads/ads'), $name2);
			$imagePath = public_path('uploads/ads/' . $name2);
			ImageOptimizer::optimize($imagePath);

			AdPostingImage::updateOrCreate(['ads_id' => $ads_id, 'image_no' => '3'], [
				'ads_id' => $ads_id,
				'image_no' => '3',
				'image' => url('public/uploads/ads') . '/' . $name2,
			]);
		}
		if ($request->hasFile('file3')) {
			$file3 = $request->file('file3');
			$name3 = time() . rand(1, 100) . '.' . $file3->extension();
			$file3->move(public_path('uploads/ads'), $name3);
			$imagePath = public_path('uploads/ads/' . $name3);
			ImageOptimizer::optimize($imagePath);

			AdPostingImage::updateOrCreate(['ads_id' => $ads_id, 'image_no' => '4'], [
				'ads_id' => $ads_id,
				'image_no' => '4',
				'image' => url('public/uploads/ads') . '/' . $name3,
			]);
		}

		if ($request->hasFile('file4')) {
			$file4 = $request->file('file4');
			$name4 = time() . rand(1, 100) . '.' . $file4->extension();
			$file4->move(public_path('uploads/ads'), $name4);
			$imagePath = public_path('uploads/ads/' . $name4);
			ImageOptimizer::optimize($imagePath);

			AdPostingImage::updateOrCreate(['ads_id' => $ads_id, 'image_no' => '5'], [
				'ads_id' => $ads_id,
				'image_no' => '5',
				'image' => url('public/uploads/ads') . '/' . $name4,
			]);
		}

		//$userprofile = new Commonform;
		$userprofile = Commonform::where('ads_id', $request->ads_id)->first();

		$userprofile->image = $adimage;
		$userprofile->ads_id = $ads_id;
		$userprofile->user_id = $request->user_id;
		$userprofile->category_id = $request->category_id;
		$userprofile->sub_category_id = $request->subcatid;
		$userprofile->formtype = $request->formtype;
		$userprofile->fullname = $request->fullname;
		$userprofile->email = $request->email;
		$userprofile->mobile = $request->mobile;
		$userprofile->location = $request->ip();
		$userprofile->state = $request->state;
		$userprofile->state_name = $request->state_name;
		$userprofile->city = $request->city;
		$userprofile->city_name = $request->city_name;
		$userprofile->neibourhood = $request->neibourhood;
		$userprofile->price = $request->price;
		$userprofile->ad_title = $request->ad_title;
		if ($subscriptionRaw->is_free == 'yes') {
			$userprofile->ad_type = 'Free';
		} else {
			$userprofile->ad_type = 'Paid';
		}
		$userprofile->description = $request->description;
		$userprofile->delete_status = '0';
		$userprofile->status = '0';
		$userprofile->save();

		// 		if($category_subscription_exists)
// 		{
// 			//$category_subscription_result = DB::table('subscription_orders')->where('category_id',$category_id)->where('user_id',$user_id)->get();
// 			$category_subscription_result = 	DB::table("subscription_history")
//         ->select("subscription_history.*")
//         ->whereRaw("find_in_set('".$category_id."',subscription_history.category_id)")
//         ->where('user_id',$user_id)
//         ->where('status','0')
//         ->get();

		// 			$used_ads 	= $category_subscription_result[0]->used_ads;
// 			$total_ads 	= $used_ads + 1;

		//         	DB::table("subscription_history")
//         ->select("subscription_history.*")
//         ->whereRaw("find_in_set('".$category_id."',subscription_history.category_id)")
//         ->where('user_id',$user_id)
//         ->where('status','0')
//         ->update(array('used_ads'=>$total_ads));

		// 		//	DB::table('subscription_orders')->where('category_id',$category_id)->where('user_id',$user_id)->update(array('used_ads'=>$total_ads));
// 		}

		\Session::put('success', 'Post Updated Successfully.');
		//return redirect("user-dashboard");
		return redirect("thank-you");
	}

	public function login(Request $request)
	{
		$data['PremiumCategories'] = Categories::where('delete_status', '0')->where('premium', '1')->orderby('id', 'desc')->get();
		$data['TopCategories'] = Categories::where('delete_status', '0')->where('top', '1')->orderby('id', 'desc')->get();
		$data['TrendingCategories'] = Categories::where('delete_status', '0')->where('trending', '1')->orderby('id', 'desc')->get();
		$data['allcategories'] = Categories::where('delete_status', '0')->orderby('name', 'ASC')->get();
		$data['Pages'] = Pages::where('delete_status', '0')->get();
		$data['city'] = City::where('delete_status', '0')->orderby('name', 'asc')->get();
		$data['countries'] = Countries::where('delete_status', '0')->orderby('name', 'asc')->get();
		$data['states'] = States::where('delete_status', '0')->orderby('name', 'asc')->get();
		$data['zip'] = Zip::where('delete_status', '0')->orderby('id', 'desc')->get();
		$userIp = $request->ip();
		$data['locationinfo'] = \Location::get($userIp);
		$data['page'] = 'Home';

		return view('website.login', $data);
	}

	public function checkEmail(Request $request)
	{
		$email = $request->input('email');
		$exists = Customer::withTrashed()->where('email', $email)->exists();

		return response()->json(['exists' => $exists]);
	}

	public function checkVerifyEmail(Request $request)
	{
		$email = $request->input('email');
		$user = Customer::withTrashed()->where('email', $email)->first();
		if (!empty($user)) {
			if ($user->is_email_verified != 1) {
				$check = 0;
			} else {
				$check = 1;
			}
		} else {
			$check = 2;
		}
		return response()->json(['check' => $check]);
	}
	public function sendVerifyLink(Request $request)
	{
		$email = $request->input('email_verify');
		$user = Customer::withTrashed()->where('email', $email)->first();

		if (!empty($user)) {
			$token = Str::random(64);
			$verify = CustomerVerify::where('customer_id', $user->id)->first();
			if (!empty($verify)) {
				CustomerVerify::where('customer_id', $user->id)->update(['token' => $token]);
			} else {
				CustomerVerify::create([
					'customer_id' => $user->id,
					'token' => $token
				]);
			}

			$mailData = ['token' => $token];
			$mailContent = Mail::to($email)->send(new EmailVerificationEmail($mailData));

			\Session::put('success', 'Check and verify your email');
			return redirect("login");
		} else {
			\Session::put('success', 'Something went wrong!');
			return redirect("login");
		}

	}

	public function purchase_subscription(Request $request)
	{
		$data['PremiumCategories'] = Categories::where('delete_status', '0')->where('premium', '1')->orderby('id', 'desc')->get();
		$data['TopCategories'] = Categories::where('delete_status', '0')->where('top', '1')->orderby('id', 'desc')->get();
		$data['TrendingCategories'] = Categories::where('delete_status', '0')->where('trending', '1')->orderby('id', 'desc')->get();
		$data['allcategories'] = Categories::where('delete_status', '0')->orderby('name', 'ASC')->get();
		$data['Pages'] = Pages::where('delete_status', '0')->get();
		$data['city'] = City::where('delete_status', '0')->get();
		$data['countries'] = Countries::where('delete_status', '0')->orderby('id', 'desc')->get();
		$data['states'] = States::where('delete_status', '0')->orderby('id', 'desc')->get();
		$data['zip'] = Zip::where('delete_status', '0')->orderby('id', 'desc')->get();
		$data['subscriptionOrder'] = SubscriptionOrder::where('user_id', session('id'))->where('subscription_expiry', NULL)->where('status', 0)->where('delete_status', 0)->first();

		//	echo "<pre/>"; print_r($data['subscriptionOrder']); die('abjkfbkef');
		if (isset($data['subscriptionOrder'])) {
			$data['subscription'] = Subscription::where('delete_status', '0')->where('status', '0')->orderby('offered_price', 'DESC')->get();
		} else {
			$data['subscription'] = Subscription::where('delete_status', '0')->where('status', '0')->orderby('offered_price', 'ASC')->get();
		}

		$userIp = $request->ip();
		$data['locationinfo'] = \Location::get($userIp);
		$data['page'] = 'Purchase Subscription';
		$data['razorpay_key'] = RazorpaySetting::first();
		return view('website.purchase_subscription', $data);
	}
	public function checkout($id)
	{
		$userid = Session::get('id');

		$data['subscription'] = Subscription::where('delete_status', '0')->where('status', '0')->orderby('offered_price', 'DESC')->findOrFail(decrypt($id));
		$subscription = SubscriptionHistory::where('user_id', $userid)
			->whereDate('subscription_expiry', '>=', Carbon::now())
			->exists();
		/** if($subscription){
			 $postedAdsCount = Adposting::where('user_id',$userid)->where('delete_status',0)->count();
			  $subscriptionhistory = SubscriptionHistory::where('user_id',$userid)->where('remaining_ads','<',$postedAdsCount)->where(function($query) use ($data) {
					 $categoryIds = explode(",", $data['subscription']->category_id);
					 foreach ($categoryIds as $categoryId) {
						 $query->orWhereRaw("FIND_IN_SET('$categoryId', category_id)");
					 }
				 })->exists();
			 if($subscriptionhistory){
				  \Session::put('error','Ads are already available to publish in your active subscription. Please use all the ads in the bucket first.');
				  return redirect()->back();
			  }
		 }**/
		$data['page'] = 'Checkout';
		$adminsetting = Adminsettings::first();
		$user = Customer::findOrFail(Session::get('id'));
		$data['customer'] = $user;

		if ($adminsetting->state_id == $user->state) {
			$data['gst_type'] = "CGST + SGST";
			$data['gst_percent'] = $adminsetting->cgst + $adminsetting->sgst;
			$data['total_gst'] = $data['subscription']->offered_price * ($adminsetting->cgst + $adminsetting->sgst) / 100;
		} else {
			$data['gst_type'] = "IGST";
			$data['gst_percent'] = $adminsetting->igst;
			$data['total_gst'] = $data['subscription']->offered_price * ($adminsetting->igst) / 100;
		}
		$totalAmount = $data['subscription']->offered_price + $data['total_gst'];
		$data['wallet'] = $user->wallet_amount;
		$data['admin_wallet_limit'] = $adminsetting->wallet_limit;
		$data['usable_wallet_amount'] = $data['subscription']->offered_price * ($data['admin_wallet_limit']) / 100;
		$data['remainingWalletBalance'] = max(0, $data['wallet'] - $data['usable_wallet_amount']);

		$data['welcome_bonus'] = $user->wallet_bonus;
		if (isset($data['welcome_bonus']) && $data['welcome_bonus'] != 0 && $data['welcome_bonus'] < $data['subscription']->offered_price) {

			if ($adminsetting->state_id == $user->state) {
				$data['AftWelDisGst'] = ($data['subscription']->offered_price - $data['welcome_bonus']) * ($adminsetting->cgst + $adminsetting->sgst) / 100;
			} else {
				$data['AftWelDisGst'] = ($data['subscription']->offered_price - $data['welcome_bonus']) * ($adminsetting->igst) / 100;
			}
			$data['totalWelWOutGst'] = ($data['subscription']->offered_price - $data['welcome_bonus']);
			$data['totalWel'] = ($data['subscription']->offered_price - $data['welcome_bonus']) + $data['AftWelDisGst'];
		}

		$data['razorpay_key'] = RazorpaySetting::first();
		$data['total'] = number_format($totalAmount, 2);
		return view('website.checkout', $data);
	}

	public function subscription_payment(Request $request, $id)
	{
		$data['PremiumCategories'] = Categories::where('delete_status', '0')->where('premium', '1')->orderby('id', 'desc')->get();
		$data['TopCategories'] = Categories::where('delete_status', '0')->where('top', '1')->orderby('id', 'desc')->get();
		$data['TrendingCategories'] = Categories::where('delete_status', '0')->where('trending', '1')->orderby('id', 'desc')->get();
		$data['allcategories'] = Categories::where('delete_status', '0')->orderby('name', 'ASC')->get();
		$data['Pages'] = Pages::where('delete_status', '0')->get();
		$data['city'] = City::where('delete_status', '0')->get();
		$data['countries'] = Countries::where('delete_status', '0')->orderby('id', 'desc')->get();
		$data['states'] = States::where('delete_status', '0')->orderby('id', 'desc')->get();
		$data['zip'] = Zip::where('delete_status', '0')->orderby('id', 'desc')->get();
		$data['subscription'] = Subscription::where('delete_status', '0')->where('status', '0')->orderby('id', 'desc')->get();
		$data['subscriptionid'] = $id;
		$userIp = $request->ip();
		$data['locationinfo'] = \Location::get($userIp);
		$data['page'] = 'Purchase Subscription';

		return view('website.subscription_payment', $data);
	}

	public function user_subscription_payment(Request $request)
	{

		$validator = Validator::make($request->all(), [
			//'card'=>'required|max:50|min:0',
		]);
		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator)->withInput();
		}
		$user_id = session('id');
		$id = $request->id;
		$rand = mt_rand(1500, 5000);

		$subscriptions = DB::table('subscriptions')->where('id', $id)->first();
		$no_of_ads = $subscriptions->no_of_ads;
		$ads_validity = $subscriptions->ads_validity;
		$category_id = $subscriptions->category_id;
		$package_validity = $subscriptions->package_validity;
		$adminsetting = Adminsettings::first();
		$no = explode(" ", $package_validity);
		//	dd($no[0]);
		$dates = date("d-m-Y");
		$date = date_create($dates);
		date_add($date, date_interval_create_from_date_string($no[0] . "days"));
		$subscription_expiry = date_format($date, "Y-m-d");

		$paymentmethod = $request->payment_method;

		if ($paymentmethod == 'online') {
			$payment_status = 'Completed';
			$transaction_id = $rand;
		}
		if ($paymentmethod == 'offline') {
			$payment_status = 'Pending';
			$transaction_id = '';
		}
		$managecommission = managecommission::where('subscription_packge_id', $request->id)->first();
		$subscriber_exists = DB::table('subscription_orders')->where('user_id', $user_id)->where('category_id', $category_id)->exists();
		if ($subscriber_exists) {
			DB::table('subscription_orders')->where('user_id', $user_id)->where('category_id', $category_id)->update(array('user_id' => $user_id, 'subscription_id' => $request->id, 'transaction_id' => $transaction_id, 'payment_method' => $request->payment_method, 'payment_status' => $payment_status, 'used_ads' => '0', 'remaining_ads' => $no_of_ads, 'subscription_validity' => $ads_validity, 'category_id' => $category_id, 'delete_status' => '0', 'status' => '0'));

		} else {


			$userprofile = new SubscriptionOrder;
			$userprofile->user_id = $user_id;
			$userprofile->subscription_id = $request->id;
			$userprofile->transaction_id = $rand;
			$userprofile->payment_method = $request->payment_method;
			$userprofile->payment_status = $payment_status;
			$userprofile->used_ads = '0';
			$userprofile->remaining_ads = $no_of_ads;
			$userprofile->subscription_expiry = $subscription_expiry;
			$userprofile->subscription_validity = $package_validity;
			$userprofile->category_id = $category_id;
			$userprofile->delete_status = '0';
			$userprofile->status = '0';
			// 			$userprofile->auto_join 				= $managecommission->auto_join;
// 			$userprofile->auto_join_member 			= $managecommission->auto_join_member;
			$userprofile->save();
		}
		$user = Customer::findOrFail(Session::get('id'));
		if ($adminsetting->state_id == $user->state) {
			$gst_type = "CGST + SGST";
			$gst_percent = $adminsetting->cgst + $adminsetting->sgst;
			$total_gst = $subscriptions->offered_price * ($adminsetting->cgst + $adminsetting->sgst) / 100;
		} else {
			$gst_type = "IGST";
			$gst_percent = $adminsetting->igst;
			$total_gst = $subscriptions->offered_price * ($adminsetting->igst) / 100;
		}


		$total_comission = ($subscriptions->offered_price * $managecommission->commission) / 100;
		$admin_charges_of_comission = ($total_comission * $adminsetting->admin_charges) / 100;
		$other_charges_of_comission = ($total_comission * $adminsetting->other_charges) / 100;
		$tds_amount_of_comission = ($user->pancard_num && $user->pancard) ? ($total_comission * $adminsetting->with_in_pan) / 100 : ($total_comission * $adminsetting->with_out_pan) / 100;
		$comission_paid_amount = $total_comission - $admin_charges_of_comission - $other_charges_of_comission - $tds_amount_of_comission;
		DB::table('subscription_history')->insert(
			array(
				'user_id' => $user_id,
				'subscription_id' => $request->id,
				'transaction_id' => $rand,
				'payment_method' => $request->payment_method,
				'payment_status' => $payment_status,
				'used_ads' => '0',
				'remaining_ads' => $no_of_ads,
				'subscription_expiry' => $subscription_expiry,
				'subscription_validity' => $package_validity,
				'category_id' => $category_id,
				'delete_status' => '0',
				'status' => '0',
				'auto_join' => $managecommission->auto_join,
				'auto_join_member' => $managecommission->auto_join_member,
				'minimum_views' => $managecommission->minimum_views,
				'mrp' => $subscriptions->mrp,
				'offered_price' => $subscriptions->offered_price,
				'discount_amount' => $subscriptions->mrp - $subscriptions->offered_price,
				'gst_type' => $gst_type,
				'gst_amount' => $total_gst,
				'order_amount_with_gst' => number_format($subscriptions->offered_price, 2) + number_format($total_gst, 2),
				'comission_paid_amount' => $comission_paid_amount,
				'tds_amount_of_comission' => $tds_amount_of_comission,
				'admin_charges_of_comission' => $admin_charges_of_comission,
				'other_charges_of_comission' => $other_charges_of_comission,
			)
		);


		$user_commission = CustomerCommission::where('parent_id', $user->parent_id)->whereYear('created_at', now()->year)
			->whereMonth('created_at', now()->month)->first();
		if (!$user_commission) {
			if ($user->parent_id) {
				DB::table('customer_commission')->insert(
					array(
						'user_id' => $user_id,
						'parent_id' => $user->parent_id,
						'subscription_id' => $request->id,
						'total_commission' => number_format($comission_paid_amount + $tds_amount_of_comission + $admin_charges_of_comission + $other_charges_of_comission, 2),
						'total_earned' => $comission_paid_amount,
						'tds' => $tds_amount_of_comission,
						'admin_charges' => $admin_charges_of_comission,
						'other_charges' => $other_charges_of_comission,
					)
				);
			}
		} else {
			DB::table('customer_commission')
				->where('parent_id', $user->parent_id)
				->update([
					'total_commission' => $user_commission->total_commission + ($comission_paid_amount + $tds_amount_of_comission + $admin_charges_of_comission + $other_charges_of_comission),
					'total_earned' => $user_commission->total_earned + $comission_paid_amount,
					'tds' => $user_commission->tds + $tds_amount_of_comission,
					'admin_charges' => $user_commission->admin_charges + $admin_charges_of_comission,
					'other_charges' => $user_commission->other_charges + $other_charges_of_comission,
				]);
		}




		/***************Level Commission save here*****/
		$levels = CommissionLevel::where('subscription_commission_id', $managecommission->id)->where('status', 1)->orderBy('id', 'ASC')->get();
		if ($managecommission->commission_level_type == 2 && isset($levels) && count($levels) > 0) {
			$levelCount = count($levels);
			$userId = $user->parent_id;
			for ($i = 0; $i < $levelCount; $i++) {
				$newUser = $this->check_parent($userId);

				if ($newUser) {

					$total_comission = ($subscriptions->offered_price) * ($levels[$i]->level_commission / 100);
					$admin_charges_of_comission = ($total_comission * $adminsetting->admin_charges) / 100;
					$other_charges_of_comission = ($total_comission * $adminsetting->other_charges) / 100;
					$tds_amount_of_comission = ($newUser->pancard_num && $newUser->pancard) ? ($total_comission * $adminsetting->with_in_pan) / 100 : ($total_comission * $adminsetting->with_out_pan) / 100;
					$comission_paid_amount = $total_comission - ($admin_charges_of_comission + $other_charges_of_comission + $tds_amount_of_comission);

					$deduction = $total_comission - $comission_paid_amount;

					$levelName = $levels[$i]->level_name;
					$levelComPer = $levels[$i]->level_commission;
					LevelTransaction::create([
						'subscription_id' => $request->id,
						'level' => $levelName,
						'from_member' => $user->id,
						'to_member' => $newUser->parent_id,
						'commission' => $levelComPer,
						'actual_amount' => $total_comission,
						'deduction' => $deduction,
						'commission_amount' => $comission_paid_amount,
					]);

					if ($newUser->parent_id) {
						$user_commission = CustomerCommission::where('parent_id', $newUser->parent_id)->whereYear('created_at', now()->year)->where('level_commission', '1')
							->whereMonth('created_at', now()->month)->first();
						if (!$user_commission) {

							DB::table('customer_commission')->insert(
								array(
									'user_id' => $user->id,
									'parent_id' => $newUser->parent_id,
									'subscription_id' => $request->id,
									'total_commission' => number_format($comission_paid_amount + $tds_amount_of_comission + $admin_charges_of_comission + $other_charges_of_comission, 2),
									'total_earned' => $comission_paid_amount,
									'tds' => $tds_amount_of_comission,
									'admin_charges' => $admin_charges_of_comission,
									'other_charges' => $other_charges_of_comission,
								)
							);

						} else {
							DB::table('customer_commission')
								->where('parent_id', $newUser->parent_id)
								->update([
									'total_commission' => $user_commission->total_commission + ($comission_paid_amount + $tds_amount_of_comission + $admin_charges_of_comission + $other_charges_of_comission),
									'total_earned' => $user_commission->total_earned + $comission_paid_amount,
									'tds' => $user_commission->tds + $tds_amount_of_comission,
									'admin_charges' => $user_commission->admin_charges + $admin_charges_of_comission,
									'other_charges' => $user_commission->other_charges + $other_charges_of_comission,
								]);
						}
						$parentUser = Customer::findOrFail($newUser->parent_id);
						$parentUser->pool_income = $parentUser->pool_income + $comission_paid_amount;
						$parentUser->save();




					}
					$userId = $newUser->parent_id;
				}


			}
		}

		/*************** Next parent level commission save End****/





		$form_id = Session::get('UserProfile')['formtype'];
		if ($form_id == 3) {
			$this->VehiclePostSubmit();

		} else if ($form_id == 1) {
			$this->JobPostSubmit();
		} else if ($form_id == 2) {
			$this->MobilePostSubmit();
		} else if ($form_id == 4) {
			$this->PropertyPostSubmit();
		} else if ($form_id == 5) {
			$this->CommonPostSubmit();
		} else {
			\Session::put('success', 'Package Purchased Successfully.');
			return redirect("user-dashboard");
		}

		\Session::put('success', 'Package Purchased Successfully.');
		return redirect("user-dashboard");
	}

	public function hide_email($email, $id)
	{
		$ads = Adposting::find($id);

		if ($email == 0) {
			$ads->is_email_hide = '0';
		} else {
			$ads->is_email_hide = '1';
		}

		$ads->save();

		$response = 'Email Hide Success';

		return response()->json($response);


	}

	public function clearAllChat(Request $request)
	{
		$roomId = $request->input('roomId');
		$userId = $request->input('userId');

		// Assuming you have a model named ChatMessage and you want to clear messages for the given room and user
		DB::table('chat_room')->where('id', $roomId)->delete();

		DB::table('chat_messages')->where('user_id', $userId)->orWhere('consumer_id', $userId)->delete();

		return response()->json(['success' => true]);
	}

	public function free_subscription(Request $request)
	{
		$user_id = Session::get('id');
		$subscription_id = $request->id;
		$walletRemaining = $request->wallet_remaining;
		$cashfree = $request->cashfree;
		$payment_id = $request->payment_id;
		$rand = mt_rand(1500, 5000);

		$result = DB::table('subscriptions')->where('id', $subscription_id)->get();
		$subscriptionTT = $result[0];
		$adminsetting = Adminsettings::first();
		$users = Customer::where('id', $user_id)->first();
		$no_of_ads = $result[0]->no_of_ads;
		$ads_validity = $result[0]->ads_validity;
		$category_id = $result[0]->category_id;
		$package_validity = $result[0]->package_validity;
		$subscription_number = $result[0]->subscription_number;
		$no = explode(" ", $package_validity);
		$dates = date("d-m-Y");
		$date = date_create($dates);
		date_add($date, date_interval_create_from_date_string($no[0] . "days"));
		$subscription_expiry = date_format($date, "Y-m-d");
		$paymentmethod = 'online';
		$payment_status = 'Completed';
		$mrp = $result[0]->mrp;
		$offered_price = $result[0]->offered_price;
		$discount_amount = $result[0]->discount;
		if ($adminsetting->state_id == $users->state) {
			$gst_type = "CGST + SGST";
			$gst_percent = $adminsetting->cgst + $adminsetting->sgst;
			$total_gst = $offered_price * ($adminsetting->cgst + $adminsetting->sgst) / 100;
		} else {
			$gst_type = "IGST";
			$gst_percent = $adminsetting->igst;
			$total_gst = $offered_price * ($adminsetting->igst) / 100;
		}

		if ($cashfree) {
			$transaction_id = $payment_id;
		} else {
			$transaction_id = $rand;
		}
		if ($subscriptionTT->is_free == 'yes') {
			$subscriber_history_free_exist = DB::table('subscription_history')->where('user_id', $user_id)->where('subscription_number', $subscriptionTT->subscription_number)->exists();
			if ($subscriber_history_free_exist) {
				return response()->json([
					'success' => false,
					'msgText' => "You are already subscribed to a free subscription..",
				]);
			}
		}


		$managecommission = managecommission::where('subscription_packge_id', $request->id)->where('delete_status', 0)->first();
		$subscriber_exists = DB::table('subscription_orders')->where('user_id', $user_id)->where('category_id', $category_id)->exists();
		$subscriber_history_check = DB::table('subscription_history')->where('user_id', $user_id)->where('type', 'Normal')->whereDate('subscription_expiry', '>', date('Y-m-d'))->exists();
		if ($subscriber_history_check) {
			$subscriber_history_free = DB::table('subscription_history')->where('user_id', $user_id)->where('subscription_number', $subscriptionTT->subscription_number)->exists();
			/**if($subscriber_history_free){
				return response()->json([
					'success' => false,
					'msgText' => "You are already subscribed to a free subscription..",
				]);
			}**/
			$totalAds = Adposting::where('user_id', $user_id)->where('delete_status', 0)->count();
			$subscriptionhistory = SubscriptionHistory::where('user_id', $user_id)->where('remaining_ads', '>', $totalAds)->where(function ($query) use ($subscriptionTT) {
				$categoryIds = explode(",", $subscriptionTT->category_id);
				foreach ($categoryIds as $categoryId) {
					$query->orWhereRaw("FIND_IN_SET('$categoryId', category_id)");
				}
			})->exists();
			/*** if($subscriptionhistory){

				 return response()->json([
					 'success' => false,
					 'msgText' => "Ads are already available to publish in your active subscription. Please use all the ads in the bucket first.",
				 ]); 
			 }
			  **/
			$fsub_remaining_ads = DB::table('subscription_history')->where('user_id', $user_id)->where('type', 'Normal')->whereDate('subscription_expiry', '>', date('Y-m-d'))->sum('remaining_ads');
			$no_of_ads = $no_of_ads + $fsub_remaining_ads;
		} else {
			$totalAds = Adposting::where('user_id', $user_id)->where('delete_status', 0)->count();

			$subscriptionhistory = SubscriptionHistory::where('user_id', $user_id)->where('remaining_ads', '>', $totalAds)->whereDate('subscription_expiry', '>=', date('Y-m-d'))->where(function ($query) use ($subscriptionTT) {
				$categoryIds = explode(",", $subscriptionTT->category_id);
				foreach ($categoryIds as $categoryId) {
					$query->orWhereRaw("FIND_IN_SET('$categoryId', category_id)");
				}
			})->exists();

			if ($subscriptionhistory) {
				$sub_remaining_ads = SubscriptionHistory::where('user_id', $user_id)->where('remaining_ads', '>', $totalAds)->whereDate('subscription_expiry', '>=', date('Y-m-d'))->where(function ($query) use ($subscriptionTT) {
					$categoryIds = explode(",", $subscriptionTT->category_id);
					foreach ($categoryIds as $categoryId) {
						$query->orWhereRaw("FIND_IN_SET('$categoryId', category_id)");
					}
				})->sum('remaining_ads');

				$no_of_ads = $no_of_ads + $sub_remaining_ads;
				/***return response()->json([
					'success' => false,
					'msgText' => "Ads are already available to publish in your active subscription. Please use all the ads in the bucket first.",
				]); **/
			}
		}


		if ($walletRemaining !== "" && isset($walletRemaining)) {
			PrimeUser::create([
				'user_id' => $user_id,
				'subscription_id' => $subscription_id,
				'total_child_count' => 0,
				'complete_child_count' => 0,
				'remaining_child_count' => $managecommission->auto_join_member,
				'status' => 'pending',
			]);
			if ($request->type == 1) {
				$walletamout = new WalletAmout();
				$walletamout->amount = $request->total_subscription;
				$walletamout->userid = $user_id;
				$walletamout->status = "2";
				$walletamout->datetime = date("d/m/y/ h:i:s A");
				$walletamout->description = $result[0]->package . " purchased using wallet";
				$walletamout->save();

				$customer = Customer::find($user_id);
				$customer->wallet_amount = $request->wallet_remaining;
				$customer->save();
			} else if ($request->type == 2) {
				$walletamout = new WalletAmout();
				$walletamout->amount = $request->total_subscription;
				$walletamout->userid = $user_id;
				$walletamout->status = "4";
				$walletamout->datetime = date("d/m/y/ h:i:s A");
				$walletamout->description = $result[0]->package . " purchased using welcome bonus";
				$walletamout->save();
				$customer = Customer::find($user_id);
				$customer->wallet_bonus = $request->wallet_remaining;
				$customer->save();
			} else if ($request->type == 3) {
				$walletamout = new WalletAmout();
				$walletamout->amount = 0;
				$walletamout->userid = $user_id;
				$walletamout->status = "2";
				$walletamout->datetime = date("d/m/y/ h:i:s A");
				$walletamout->description = $result[0]->package . " purchased for free";
				$walletamout->save();
			}
			if ($cashfree == 1) {
				$walletamout = new WalletAmout();
				$walletamout->amount = $request->total_subscription;
				$walletamout->userid = $user_id;
				$walletamout->status = "2";
				$walletamout->datetime = date("d/m/y/ h:i:s A");
				$walletamout->description = $result[0]->package . " purchased using cashfree of the amount ₹" . ($request->total_subscription);
				$walletamout->save();
			} else if ($cashfree == 2) {
				$walletamout = new WalletAmout();
				$walletamout->amount = $request->total_subscription;
				$walletamout->userid = $user_id;
				$walletamout->status = "4";
				$walletamout->datetime = date("d/m/y/ h:i:s A");
				$walletamout->description = $result[0]->package . " purchased using cashfree of ₹" . $request->total_subscription . " and welcome bonus of ₹" . (($total_gst + $offered_price) - $request->total_subscription);
				$walletamout->save();
			}
		}
		if (!isset($cashfree) && !$cashfree == 1 || !$cashfree == 2) {
			$userprofile = new SubscriptionOrder;
			$userprofile->user_id = $user_id;
			$userprofile->subscription_id = $request->id;
			$userprofile->transaction_id = $transaction_id;
			$userprofile->payment_method = $paymentmethod;
			$userprofile->payment_status = $payment_status;
			$userprofile->used_ads = '0';
			$userprofile->remaining_ads = $no_of_ads;
			$userprofile->subscription_expiry = $subscription_expiry;
			$userprofile->subscription_validity = $package_validity;
			$userprofile->category_id = $category_id;
			$userprofile->delete_status = '0';
			$userprofile->status = '0';
			$userprofile->save();
		}
		$data = [
			'user_id' => $user_id,
			'subscription_id' => $request->id,
			'transaction_id' => $transaction_id,
			'payment_method' => $paymentmethod,
			'payment_method' => $paymentmethod,
			'payment_status' => $payment_status,
			'used_ads' => '0',
			'remaining_ads' => $no_of_ads,
			'subscription_expiry' => $subscription_expiry,
			'subscription_validity' => $package_validity,
			'category_id' => $category_id,
			'delete_status' => '0',
			'status' => '0',
			'subscription_number' => $subscription_number,
			'order_number' => 'ORD' . rand(100000, 9999999),
			'auto_join' => $managecommission->auto_join ?? 0,
			'auto_join_member' => $managecommission->auto_join_member ?? 0,
		];
		$user_commission = CustomerCommission::where('user_id', $user_id)->where('parent_id', $users->parent_id)->whereYear('created_at', now()->year)
			->whereMonth('created_at', now()->month)->first();
		// Conditionally add fields related to subscription if $wallet_remaining is not empty

		if (!isset($request->type) || $request->type != 2 && $walletRemaining !== "" && isset($walletRemaining)) {
			$total_comission = ($request->total_wout_gst ?? $request->total_subscription ?? $offered_price) * ($managecommission->commission / 100);
			$admin_charges_of_comission = ($total_comission * $adminsetting->admin_charges) / 100;
			$other_charges_of_comission = ($total_comission * $adminsetting->other_charges) / 100;
			$tds_amount_of_comission = ($users->pancard_num && $users->pancard) ? ($total_comission * $adminsetting->with_in_pan) / 100 : ($total_comission * $adminsetting->with_out_pan) / 100;
			$comission_paid_amount = $total_comission - ($admin_charges_of_comission + $other_charges_of_comission + $tds_amount_of_comission);

			if ($users->parent_id && $users->reserve_expiry_at >= date('Y-m-d')) {
				DB::table('customer_commission')->insert(
					array(
						'user_id' => $user_id,
						'parent_id' => $users->parent_id,
						'subscription_id' => $request->id,
						'total_commission' => number_format($total_comission, 2),
						'total_earned' => $comission_paid_amount,
						'tds' => $tds_amount_of_comission,
						'admin_charges' => $admin_charges_of_comission,
						'other_charges' => $other_charges_of_comission,
					)
				);
				$parentUser = Customer::findOrFail($users->parent_id);
				$parentUser->wallet_amount = $parentUser->wallet_amount + $comission_paid_amount;
				$parentUser->save();

				$walletamout = new WalletAmout();
				$walletamout->amount = $comission_paid_amount;
				$walletamout->userid = $parentUser->id;
				$walletamout->status = "1";
				$walletamout->datetime = date("d/m/y/ h:i:s A");
				$walletamout->description = "Credit by " . $users->member_id . " Commission from Seeds Purchasing";
				$walletamout->save();

				$event = DefaultNotification::where('event', 'wallet_credit')->first();
				if (!empty($event)) {
					$title = $event->title;
					$content = $event->content;
					$body = str_replace("#amount", $comission_paid_amount, $content);
					$notifyArray = array(
						'user_id' => $parentUser->id,
						'event_name' => $event->event,
						'title' => $title,
						'body' => $body,
					);

					$this->singleUserNotification($notifyArray);
				}

				if ($parentUser->fcm_token) {
					$title = 'Commission Recevied!';
					$body = $users->name . ' purchased a subscription';
					$image = null;
					$response = $this->sendNotification($title, $body, $parentUser->fcm_token, $image);
				}
			}
			$data['type'] = 'Prime';
			$data['mrp'] = $mrp;
			$data['minimum_views'] = $managecommission->minimum_views;
			$data['gst_type'] = $gst_type;
			$data['offered_price'] = $offered_price;
			$data['gst_amount'] = $total_gst;
			if ($cashfree == 1 || $cashfree == 2) {
				$data['payment_mode'] = 'Cashfreepay';
			} else {
				$data['payment_mode'] = 'Wallet';
			}
			$data['discount_amount'] = $discount_amount;
			$data['order_amount_with_gst'] = number_format($offered_price, 2) + number_format($total_gst, 2);


			/***************Level Commission save here*****/
			$levels = CommissionLevel::where('subscription_commission_id', $managecommission->id)->where('status', 1)->orderBy('id', 'ASC')->get();
			if ($managecommission->commission_level_type == 2 && isset($levels) && count($levels) > 0) {
				$levelCount = count($levels);
				$userId = $users->parent_id;
				for ($i = 0; $i < $levelCount; $i++) {
					$newUser = $this->check_parent($userId);

					if ($newUser) {

						$total_comission = ($request->total_wout_gst ?? $request->total_subscription ?? $offered_price) * ($levels[$i]->level_commission / 100);
						$admin_charges_of_comission = ($total_comission * $adminsetting->admin_charges) / 100;
						$other_charges_of_comission = ($total_comission * $adminsetting->other_charges) / 100;
						$tds_amount_of_comission = ($newUser->pancard_num && $newUser->pancard) ? ($total_comission * $adminsetting->with_in_pan) / 100 : ($total_comission * $adminsetting->with_out_pan) / 100;
						$comission_paid_amount = $total_comission - ($admin_charges_of_comission + $other_charges_of_comission + $tds_amount_of_comission);

						$deduction = $total_comission - $comission_paid_amount;

						$levelName = $levels[$i]->level_name;
						$levelComPer = $levels[$i]->level_commission;
						$levelT = LevelTransaction::create([
							'subscription_id' => $request->id,
							'level' => $levelName,
							'from_member' => $users->id,
							'to_member' => $newUser->parent_id,
							'commission' => $levelComPer,
							'actual_amount' => $total_comission,
							'deduction' => $deduction,
							'commission_amount' => $comission_paid_amount,
						]);

						if ($newUser->parent_id) {
							DB::table('customer_commission')->insert(
								array(
									'user_id' => $users->id,
									'parent_id' => $newUser->parent_id,
									'subscription_id' => $request->id,
									'total_commission' => number_format($total_comission, 2),
									'total_earned' => $comission_paid_amount,
									'tds' => $tds_amount_of_comission,
									'admin_charges' => $admin_charges_of_comission,
									'other_charges' => $other_charges_of_comission,
									'level_commission' => '1',
									'level_transaction_id' => $levelT->id
								)
							);

							$parentUser = Customer::findOrFail($newUser->parent_id);
							$parentUser->wallet_amount = $parentUser->wallet_amount + $comission_paid_amount;
							$parentUser->pool_income = $parentUser->pool_income + $comission_paid_amount;
							$parentUser->save();

							$walletamout = new WalletAmout();
							$walletamout->amount = $comission_paid_amount;
							$walletamout->userid = $parentUser->id;
							$walletamout->status = "1";
							$walletamout->datetime = date("d/m/y/ h:i:s A");
							$walletamout->description = "Credit by " . $newUser->member_id . "Pool Commission from Seeds Purchasing";
							$walletamout->save();

							$event = DefaultNotification::where('event', 'wallet_credit')->first();
							if (!empty($event)) {
								$title = $event->title;
								$content = $event->content;
								$body = str_replace("#amount", $comission_paid_amount, $content);
								$notifyArray = array(
									'user_id' => $parentUser->id,
									'event_name' => $event->event,
									'title' => $title,
									'body' => $body,
								);

								$this->singleUserNotification($notifyArray);
							}

							if ($parentUser->fcm_token) {
								$title = 'Commission Recevied!';
								$body = $newUser->name . ' purchased a subscription';
								$image = null;
								$response = $this->sendNotification($title, $body, $parentUser->fcm_token, $image);
							}
						}
						$userId = $newUser->parent_id;
					}


				}
			}

			/*************** Next parent level commission save End****/

		}

		$subId = DB::table('subscription_history')->insertGetId($data);

		if (Session::has('UserProfile') && !empty(Session::get('UserProfile'))) {
			Session::put('SubscriptionData', ['id' => $subId]);
			$form_id = Session::get('UserProfile')['formtype'];
			if ($form_id == 3) {
				$this->VehiclePostSubmit();
			} else if ($form_id == 1) {
				$this->JobPostSubmit();
			} else if ($form_id == 2) {
				$this->MobilePostSubmit();
			} else if ($form_id == 4) {
				$this->PropertyPostSubmit();
			} else if ($form_id == 5) {
				$this->CommonPostSubmit();
			} else {
				return response()->json([
					'success' => true,
					'msgText' => 'Package Purchased Successfully.',
				]);
			}
		}
		$event1 = DefaultNotification::where('event', 'subscription_activated')->first();
		if (!empty($event1)) {
			$title = $event1->title;
			$body = $event1->content;
			$notifyArray = array(
				'user_id' => $user_id,
				'event_name' => $event1->event,
				'title' => $title,
				'body' => $body,
			);

			$this->singleUserNotification($notifyArray);
		}
		if (isset($users->fcm_token)) {
			$title = 'Congratulations';
			$body = $subscriptionTT->package . ' Package Purchased Successfully.';
			$image = null;
			$response = $this->sendNotification($title, $body, $users->fcm_token, $image);
		}
		if ($cashfree == 1 || $cashfree == 2) {
			\Session::put('success', 'Package Purchased Successfully.');
			return redirect("purchase-subscription");
		}
		return response()->json([
			'success' => true,
			'msgText' => 'Package Purchased Successfully.',
		]);


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

	public function razorpaystore(Request $request)
	{
		$input = $request->all();
		$subscription_id = $request->id;
		$setting = RazorpaySetting::first();
		$api = new Api($setting->key_id, $setting->secret_id);
		$payment = $api->payment->fetch($input['razorpay_payment_id']);

		$user_id = session('id');
		$users = Customer::where('id', $user_id)->first();
		//$rand 		        = mt_rand(1500, 5000);
		$subscriptions = DB::table('subscriptions')->where('id', $subscription_id)->first();
		$no_of_ads = $subscriptions->no_of_ads;
		$ads_validity = $subscriptions->ads_validity;
		$category_id = $subscriptions->category_id;
		$package_validity = $subscriptions->package_validity;
		$subscription_number = $subscriptions->subscription_number;
		$no = explode(" ", $package_validity);
		$dates = date("d-m-Y");
		$adminsetting = Adminsettings::first();
		$date = date_create($dates);
		date_add($date, date_interval_create_from_date_string($no[0] . "days"));
		$subscription_expiry = date_format($date, "Y-m-d");
		$managecommission = managecommission::where('subscription_packge_id', $request->id)->first();
		$paymentmethod = 'onlineprime';
		$payment_status = 'Completed';
		$transaction_id = $input['razorpay_payment_id'];

		if ($request->wallet_remaining !== "" && isset($request->wallet_remaining)) {
			$walletamout = new WalletAmout();
			$walletamout->amount = $request->total_subscription;
			$walletamout->userid = $user_id;
			$walletamout->status = "2";
			$walletamout->datetime = date("d/m/y/ h:i:s A");
			$walletamout->description = $subscriptions->package . " purchased using wallet and razorpay";
			$walletamout->save();

			$customer = Customer::find($user_id);
			$customer->wallet_amount = $request->wallet_remaining;
			$customer->save();
			$this->testuser();
		}
		//add in prime table ids

		$manage_commision_id = managecommission::where('subscription_packge_id', $input['id'])->first();
		//dd($manage_commision_id->auto_join_member);
		PrimeUser::create([
			'user_id' => $user_id,
			'subscription_id' => $input['id'],
			'total_child_count' => 0,
			'complete_child_count' => 0,
			'remaining_child_count' => $manage_commision_id->auto_join_member,
			'status' => 'pending',
		]);

		if (count($input) && !empty($input['razorpay_payment_id'])) {
			try {
				$response = $api->payment->fetch($input['razorpay_payment_id'])->capture(array('amount' => $payment['amount']));

				//  echo "<pre/>"; print_r($response); die('ajhsbj');
				$subscriber_exists = DB::table('subscription_orders')->where('user_id', $user_id)->where('category_id', $category_id)->exists();
				// echo "<pre/>"; print_r($subscriber_exists); die('ajhsbj');
				if ($subscriber_exists) {
					DB::table('subscription_orders')->where('user_id', $user_id)->where('category_id', $category_id)->update(array('user_id' => $user_id, 'subscription_id' => $request->id, 'transaction_id' => $transaction_id, 'payment_method' => $paymentmethod, 'payment_status' => $payment_status, 'used_ads' => '0', 'remaining_ads' => $no_of_ads, 'subscription_validity' => $ads_validity, 'category_id' => $category_id, 'delete_status' => '0', 'status' => '0'));

				} else {
					// die('else condition');
					$userprofile = new SubscriptionOrder;
					$userprofile->user_id = $user_id;
					$userprofile->subscription_id = $request->id;
					$userprofile->transaction_id = $transaction_id;
					$userprofile->payment_method = $paymentmethod;
					$userprofile->payment_status = $payment_status;
					$userprofile->used_ads = '0';
					$userprofile->remaining_ads = $no_of_ads;
					$userprofile->subscription_expiry = $subscription_expiry;
					$userprofile->subscription_validity = $package_validity;
					$userprofile->category_id = $category_id;
					$userprofile->delete_status = '0';
					// 			$userprofile->auto_join 				= $managecommission->auto_join;
					//  	$userprofile->auto_join_member 			= $managecommission->auto_join_member;
					$userprofile->status = '0';
					$userprofile->save();
				}
				$user = Customer::findOrFail(Session::get('id'));
				if ($adminsetting->state_id == $user->state) {
					$gst_type = "CGST + SGST";
					$gst_percent = $adminsetting->cgst + $adminsetting->sgst;
					$total_gst = $subscriptions->offered_price * ($adminsetting->cgst + $adminsetting->sgst) / 100;
				} else {
					$gst_type = "IGST";
					$gst_percent = $adminsetting->igst;
					$total_gst = $subscriptions->offered_price * ($adminsetting->igst) / 100;
				}
				$total_comission = ($subscriptions->offered_price * $managecommission->commission) / 100;
				$admin_charges_of_comission = ($total_comission * $adminsetting->admin_charges) / 100;
				$other_charges_of_comission = ($total_comission * $adminsetting->other_charges) / 100;
				$tds_amount_of_comission = ($user->pancard_num && $user->pancard) ? ($total_comission * $adminsetting->with_in_pan) / 100 : ($total_comission * $adminsetting->with_out_pan) / 100;
				$comission_paid_amount = $total_comission - $admin_charges_of_comission - $other_charges_of_comission - $tds_amount_of_comission;
				DB::table('subscription_history')->insert(
					array(
						'user_id' => $user_id,
						'subscription_id' => $request->id,
						'transaction_id' => $transaction_id,
						'payment_method' => $paymentmethod,
						'payment_status' => $payment_status,
						'used_ads' => '0',
						'type' => 'Prime',
						'remaining_ads' => $no_of_ads,
						'subscription_expiry' => $subscription_expiry,
						'subscription_validity' => $package_validity,
						'category_id' => $category_id,
						'delete_status' => '0',
						'status' => '0',
						'auto_join' => $managecommission->auto_join,
						'auto_join_member' => $managecommission->auto_join_member,
						'minimum_views' => $managecommission->minimum_views,
						'mrp' => $subscriptions->mrp,
						'offered_price' => $subscriptions->offered_price,
						'discount_amount' => $subscriptions->mrp - $subscriptions->offered_price,
						'gst_type' => $gst_type,
						'gst_amount' => $total_gst,
						'order_amount_with_gst' => number_format($subscriptions->offered_price, 2) + number_format($total_gst, 2),
						'comission_paid_amount' => $comission_paid_amount,
						'tds_amount_of_comission' => $tds_amount_of_comission,
						'admin_charges_of_comission' => $admin_charges_of_comission,
						'other_charges_of_comission' => $other_charges_of_comission,
						'comission_paid_parent_id' => $users->parent_id,
						'payment_mode' => 'razorpay',
						'subscription_number' => $subscription_number,
						'order_number' => 'ORD' . rand(100000, 9999999),
						'comission_paid' => $users->parent_id ? "yes" : "no",
					)
				);
				$user_commission = CustomerCommission::where('parent_id', $user->parent_id)->whereYear('created_at', now()->year)
					->whereMonth('created_at', now()->month)->first();
				if (!$user_commission) {
					if ($user->parent_id) {
						DB::table('customer_commission')->insert(
							array(
								'user_id' => $user_id,
								'parent_id' => $user->parent_id,
								'subscription_id' => $request->id,
								'total_commission' => number_format($comission_paid_amount + $tds_amount_of_comission + $admin_charges_of_comission + $other_charges_of_comission, 2),
								'total_earned' => $comission_paid_amount,
								'tds' => $tds_amount_of_comission,
								'admin_charges' => $admin_charges_of_comission,
								'other_charges' => $other_charges_of_comission,
							)
						);
					}
				} else {
					DB::table('customer_commission')
						->where('parent_id', $user->parent_id)
						->update([
							'total_commission' => $user_commission->total_commission + ($comission_paid_amount + $tds_amount_of_comission + $admin_charges_of_comission + $other_charges_of_comission),
							'total_earned' => $user_commission->total_earned + $comission_paid_amount,
							'tds' => $user_commission->tds + $tds_amount_of_comission,
							'admin_charges' => $user_commission->admin_charges + $admin_charges_of_comission,
							'other_charges' => $user_commission->other_charges + $other_charges_of_comission,
						]);
				}

				if (Session::has('UserProfile') && !empty(Session::get('UserProfile'))) {
					$form_id = Session::get('UserProfile')['formtype'];
					if ($form_id == 3) {
						$this->VehiclePostSubmit();
					} else if ($form_id == 1) {
						$this->JobPostSubmit();
					} else if ($form_id == 2) {
						$this->MobilePostSubmit();
					} else if ($form_id == 4) {
						$this->PropertyPostSubmit();
					} else if ($form_id == 5) {
						$this->CommonPostSubmit();
					} else {
						return response()->json([
							'success' => true,
							'msgText' => 'Package Purchased Successfully.',
						]);
					}
				} else {
					\Session::put('success', 'Package Purchased Successfully.');
					return redirect("purchase-subscription");
				}

			} catch (Exception $e) {
				return $e->getMessage();
				Session::put('error', $e->getMessage());
				return redirect()->back();
			}
		}
	}


	public function WalletAmout($request)
	{

		$userid = session('id');
		$customer = Customer::find('id', $userid);
		if (!empty($customer)) {
			$subcription = Subscription::where("id", $request->id)->first();
			$subscriptionOrder = managecommission::where("subscription_id", $subcription->package)->first();
			$amount = ($subcription->offered_price * $subscriptionOrder->commission) / 100;
			$myrefral = Customer::where('referral_code', $customer->referralto)->first();

			$walletamout = new WalletAmout();
			$walletamout->amount = $amount;
			$walletamout->userid = $myrefral->id;
			$walletamout->status = "1";
			$walletamout->datetime = date("d/m/y/ h:i:s A");
			$walletamout->save();

		}
	}

	public function VehiclePostSubmit()
	{
		$userprofile = new Adposting;
		$userprofile->ad_id = Session::get('UserProfile')['ad_id'];
		$userprofile->subscription_id = Session::get('SubscriptionData')['id'];
		$userprofile->user_id = Session::get('UserProfile')['user_id'];
		$userprofile->image = Session::get('UserProfile')['image'];
		$userprofile->category_id = Session::get('UserProfile')['category_id'];
		$userprofile->sub_category_id = Session::get('UserProfile')['sub_category_id'];
		$userprofile->formtype = Session::get('UserProfile')['formtype'];
		$userprofile->fullname = Session::get('UserProfile')['fullname'];
		$userprofile->email = Session::get('UserProfile')['email'];
		$userprofile->mobile = Session::get('UserProfile')['mobile'];
		$userprofile->location = Session::get('UserProfile')['location'];
		$userprofile->city = Session::get('UserProfile')['city'];
		$userprofile->price = Session::get('UserProfile')['price'];
		$userprofile->ad_title = Session::get('UserProfile')['ad_title'];
		$userprofile->ad_type = Session::get('UserProfile')['ad_type'];
		//$userprofile->ad_expiry 		= $subscription_expiry;
		$userprofile->active_status = Session::get('UserProfile')['active_status'];
		$userprofile->ad_view_count = Session::get('UserProfile')['ad_view_count'];
		$userprofile->ads_validity = Session::get('UserProfile')['ads_validity'];
		$userprofile->description = Session::get('UserProfile')['description'];
		$userprofile->delete_status = Session::get('UserProfile')['delete_status'];
		$userprofile->status = Session::get('UserProfile')['status'];

		$userprofile->save();

		$ads_id = $userprofile->ad_id;
		$imageKeys = ['image1', 'image2', 'image3', 'image4', 'image5', 'image6', 'image7', 'image8'];

		foreach ($imageKeys as $key => $imageKey) {
			if (Session::has('ImageAd.' . $imageKey)) {
				AdPostingImage::create([
					'ads_id' => $ads_id,
					'image' => Session::get('ImageAd.' . $imageKey),
					'image_no' => $key + 1,
				]);
			}
		}

		$userprofile = new Vehicleform;

		//	$userprofile->image 			= $adimage; 
		$userprofile->ads_id = $ads_id;
		$userprofile->user_id = Session::get('VehicleDetail')['user_id'];
		$userprofile->category_id = Session::get('VehicleDetail')['category_id'];
		$userprofile->sub_category_id = Session::get('VehicleDetail')['sub_category_id'];
		$userprofile->formtype = Session::get('VehicleDetail')['formtype'];
		$userprofile->fullname = Session::get('VehicleDetail')['fullname'];
		$userprofile->email = Session::get('VehicleDetail')['email'];
		$userprofile->mobile = Session::get('VehicleDetail')['mobile'];
		$userprofile->location = Session::get('VehicleDetail')['location'];
		$userprofile->state = Session::get('VehicleDetail')['state'];
		$userprofile->state_name = Session::get('VehicleDetail')['state_name'];
		$userprofile->city = Session::get('VehicleDetail')['city'];
		$userprofile->city_name = Session::get('VehicleDetail')['city_name'];
		$userprofile->neibourhood = Session::get('VehicleDetail')['neibourhood'];
		$userprofile->price = Session::get('VehicleDetail')['price'];
		$userprofile->ad_title = Session::get('VehicleDetail')['ad_title'];
		$userprofile->ad_type = Session::get('VehicleDetail')['ad_type'];
		$userprofile->description = Session::get('VehicleDetail')['description'];
		$userprofile->brand = Session::get('VehicleDetail')['brand'];
		$userprofile->vehicle_type = Session::get('VehicleDetail')['vehicle_type'];
		$userprofile->fuel_type = Session::get('VehicleDetail')['fuel_type'];
		$userprofile->transmission = Session::get('VehicleDetail')['transmission'];
		$userprofile->year = Session::get('VehicleDetail')['year'];
		$userprofile->km = Session::get('VehicleDetail')['km'];
		$userprofile->delete_status = Session::get('VehicleDetail')['delete_status'];
		$userprofile->status = Session::get('VehicleDetail')['status'];
		$userprofile->save();

		$category_subscription_exists = DB::table('subscription_history')->where('category_id', Session::get('VehicleDetail')['category_id'])->where('user_id', Session::get('VehicleDetail')['user_id'])->exists();

		if ($category_subscription_exists) {
			$category_subscription_result = DB::table('subscription_history')->where('category_id', Session::get('VehicleDetail')['category_id'])->where('user_id', Session::get('VehicleDetail')['user_id'])->get();
			$used_ads = $category_subscription_result[0]->used_ads;
			$total_ads = $used_ads + 1;
			DB::table('subscription_history')->where('category_id', Session::get('VehicleDetail')['category_id'])->where('user_id', Session::get('VehicleDetail')['user_id'])->update(array('used_ads' => $total_ads));
		}

		Session::forget('UserProfile');
		Session::forget('VehicleDetail');
		Session::forget('SubscriptionData');
		Session::forget('ImageAd');
		Session::put('form-submitted', 'Form posted successfully. Thank You');
		return redirect("thank-you");
	}

	public function JobPostSubmit()
	{
		$userprofile = new Adposting;
		$userprofile->ad_id = Session::get('UserProfile')['ad_id'];
		$userprofile->subscription_id = Session::get('SubscriptionData')['id'];
		$userprofile->user_id = Session::get('UserProfile')['user_id'];
		$userprofile->category_id = Session::get('UserProfile')['category_id'];
		$userprofile->image = Session::get('UserProfile')['image'];
		$userprofile->sub_category_id = Session::get('UserProfile')['sub_category_id'];
		$userprofile->formtype = Session::get('UserProfile')['formtype'];
		$userprofile->fullname = Session::get('UserProfile')['fullname'];
		$userprofile->email = Session::get('UserProfile')['email'];
		$userprofile->mobile = Session::get('UserProfile')['mobile'];
		$userprofile->location = Session::get('UserProfile')['location'];
		$userprofile->city = Session::get('UserProfile')['city'];
		$userprofile->salary_from = Session::get('UserProfile')['salary_from'];
		$userprofile->salary_to = Session::get('UserProfile')['salary_to'];
		$userprofile->ad_title = Session::get('UserProfile')['ad_title'];
		$userprofile->ad_type = Session::get('UserProfile')['ad_type'];
		//$userprofile->ad_expiry 		= $subscription_expiry;
		$userprofile->active_status = Session::get('UserProfile')['active_status'];
		$userprofile->ad_view_count = Session::get('UserProfile')['ad_view_count'];
		$userprofile->ads_validity = Session::get('UserProfile')['ads_validity'];
		$userprofile->description = Session::get('UserProfile')['description'];
		$userprofile->delete_status = Session::get('UserProfile')['delete_status'];
		$userprofile->status = Session::get('UserProfile')['status'];

		$userprofile->save();

		$ads_id = $userprofile->ad_id;
		$imageKeys = ['image1', 'image2', 'image3', 'image4', 'image5', 'image6', 'image7', 'image8'];

		foreach ($imageKeys as $key => $imageKey) {
			if (Session::has('ImageAd.' . $imageKey)) {
				AdPostingImage::create([
					'ads_id' => $ads_id,
					'image' => Session::get('ImageAd.' . $imageKey),
					'image_no' => $key + 1,
				]);
			}
		}
		$userprofile = new Jobforms;

		//	$userprofile->image 			= $adimage; 
		$userprofile->ads_id = $ads_id;
		$userprofile->user_id = Session::get('JobDetail')['user_id'];
		$userprofile->category_id = Session::get('JobDetail')['category_id'];
		$userprofile->sub_category_id = Session::get('JobDetail')['sub_category_id'];
		$userprofile->formtype = Session::get('JobDetail')['formtype'];
		$userprofile->fullname = Session::get('JobDetail')['fullname'];
		$userprofile->email = Session::get('JobDetail')['email'];
		$userprofile->mobile = Session::get('JobDetail')['mobile'];
		$userprofile->location = Session::get('JobDetail')['location'];
		$userprofile->state = Session::get('JobDetail')['state'];
		$userprofile->state_name = Session::get('JobDetail')['state_name'];
		$userprofile->city = Session::get('JobDetail')['city'];
		$userprofile->city_name = Session::get('JobDetail')['city_name'];
		$userprofile->neibourhood = Session::get('JobDetail')['neibourhood'];
		$userprofile->salary_period = Session::get('JobDetail')['salary_period'];
		$userprofile->position_type = Session::get('JobDetail')['position_type'];
		$userprofile->salary_from = Session::get('JobDetail')['salary_from'];
		$userprofile->salary_to = Session::get('JobDetail')['salary_to'];
		$userprofile->ad_title = Session::get('JobDetail')['ad_title'];
		$userprofile->ad_type = Session::get('JobDetail')['ad_type'];
		$userprofile->description = Session::get('JobDetail')['description'];
		$userprofile->delete_status = Session::get('JobDetail')['delete_status'];
		$userprofile->status = Session::get('JobDetail')['status'];
		$userprofile->save();

		$category_subscription_exists = DB::table('subscription_history')->where('category_id', Session::get('JobDetail')['category_id'])->where('user_id', Session::get('JobDetail')['user_id'])->exists();

		if ($category_subscription_exists) {
			$category_subscription_result = DB::table('subscription_history')->where('category_id', Session::get('JobDetail')['category_id'])->where('user_id', Session::get('JobDetail')['user_id'])->get();
			$used_ads = $category_subscription_result[0]->used_ads;
			$total_ads = $used_ads + 1;
			DB::table('subscription_history')->where('category_id', Session::get('JobDetail')['category_id'])->where('user_id', Session::get('JobDetail')['user_id'])->update(array('used_ads' => $total_ads));
		}

		Session::forget('UserProfile');
		Session::forget('JobDetail');
		Session::forget('SubscriptionData');
		Session::forget('ImageAd');
		Session::put('form-submitted', 'Form posted successfully. Thank You');

		return redirect("thank-you");
	}

	public function MobilePostSubmit()
	{
		$userprofile = new Adposting;
		$userprofile->ad_id = Session::get('UserProfile')['ad_id'];
		$userprofile->subscription_id = Session::get('SubscriptionData')['id'];
		$userprofile->user_id = Session::get('UserProfile')['user_id'];
		$userprofile->category_id = Session::get('UserProfile')['category_id'];
		$userprofile->image = Session::get('UserProfile')['image'];
		$userprofile->sub_category_id = Session::get('UserProfile')['sub_category_id'];
		$userprofile->formtype = Session::get('UserProfile')['formtype'];
		$userprofile->fullname = Session::get('UserProfile')['fullname'];
		$userprofile->email = Session::get('UserProfile')['email'];
		$userprofile->mobile = Session::get('UserProfile')['mobile'];
		$userprofile->location = Session::get('UserProfile')['location'];
		$userprofile->city = Session::get('UserProfile')['city'];
		$userprofile->price = Session::get('UserProfile')['price'];
		$userprofile->ad_title = Session::get('UserProfile')['ad_title'];
		$userprofile->ad_type = Session::get('UserProfile')['ad_type'];
		//$userprofile->ad_expiry 		= $subscription_expiry;
		$userprofile->active_status = Session::get('UserProfile')['active_status'];
		$userprofile->ad_view_count = Session::get('UserProfile')['ad_view_count'];
		$userprofile->ads_validity = Session::get('UserProfile')['ads_validity'];
		$userprofile->description = Session::get('UserProfile')['description'];
		$userprofile->delete_status = Session::get('UserProfile')['delete_status'];
		$userprofile->status = Session::get('UserProfile')['status'];

		$userprofile->save();

		$ads_id = $userprofile->ad_id;

		$imageKeys = ['image1', 'image2', 'image3', 'image4', 'image5', 'image6', 'image7', 'image8'];

		foreach ($imageKeys as $key => $imageKey) {
			if (Session::has('ImageAd.' . $imageKey)) {
				AdPostingImage::create([
					'ads_id' => $ads_id,
					'image' => Session::get('ImageAd.' . $imageKey),
					'image_no' => $key + 1,
				]);
			}
		}

		$userprofile = new Mobileform;

		//	$userprofile->image 			= $adimage; 
		$userprofile->ads_id = $ads_id;
		$userprofile->user_id = Session::get('MobileDetail')['user_id'];
		$userprofile->category_id = Session::get('MobileDetail')['category_id'];
		$userprofile->sub_category_id = Session::get('MobileDetail')['sub_category_id'];
		$userprofile->formtype = Session::get('MobileDetail')['formtype'];
		$userprofile->fullname = Session::get('MobileDetail')['fullname'];
		$userprofile->email = Session::get('MobileDetail')['email'];
		$userprofile->mobile = Session::get('MobileDetail')['mobile'];
		$userprofile->location = Session::get('MobileDetail')['location'];
		$userprofile->state = Session::get('MobileDetail')['state'];
		$userprofile->state_name = Session::get('MobileDetail')['state_name'];
		$userprofile->city = Session::get('MobileDetail')['city'];
		$userprofile->city_name = Session::get('MobileDetail')['city_name'];
		$userprofile->neibourhood = Session::get('MobileDetail')['neibourhood'];
		$userprofile->brand = Session::get('MobileDetail')['brand'];
		$userprofile->ad_title = Session::get('MobileDetail')['ad_title'];
		$userprofile->ad_type = Session::get('MobileDetail')['ad_type'];
		$userprofile->description = Session::get('MobileDetail')['description'];
		$userprofile->delete_status = Session::get('MobileDetail')['delete_status'];
		$userprofile->status = Session::get('MobileDetail')['status'];
		$userprofile->save();

		$category_subscription_exists = DB::table('subscription_history')->where('category_id', Session::get('MobileDetail')['category_id'])->where('user_id', Session::get('MobileDetail')['user_id'])->exists();

		if ($category_subscription_exists) {
			$category_subscription_result = DB::table('subscription_history')->where('category_id', Session::get('MobileDetail')['category_id'])->where('user_id', Session::get('MobileDetail')['user_id'])->get();
			$used_ads = $category_subscription_result[0]->used_ads;
			$total_ads = $used_ads + 1;
			DB::table('subscription_history')->where('category_id', Session::get('MobileDetail')['category_id'])->where('user_id', Session::get('MobileDetail')['user_id'])->update(array('used_ads' => $total_ads));
		}

		Session::forget('UserProfile');
		Session::forget('MobileDetail');
		Session::forget('SubscriptionData');
		Session::forget('ImageAd');
		Session::put('form-submitted', 'Form posted successfully. Thank You');

		return redirect("thank-you");
	}

	public function PropertyPostSubmit()
	{
		$userprofile = new Adposting;
		$userprofile->ad_id = Session::get('UserProfile')['ad_id'];
		$userprofile->subscription_id = Session::get('SubscriptionData')['id'];
		$userprofile->user_id = Session::get('UserProfile')['user_id'];
		$userprofile->category_id = Session::get('UserProfile')['category_id'];
		$userprofile->image = Session::get('UserProfile')['image'];
		$userprofile->sub_category_id = Session::get('UserProfile')['sub_category_id'];
		$userprofile->formtype = Session::get('UserProfile')['formtype'];
		$userprofile->fullname = Session::get('UserProfile')['fullname'];
		$userprofile->email = Session::get('UserProfile')['email'];
		$userprofile->mobile = Session::get('UserProfile')['mobile'];
		$userprofile->location = Session::get('UserProfile')['location'];
		$userprofile->city = Session::get('UserProfile')['city'];
		$userprofile->price = Session::get('UserProfile')['price'];
		$userprofile->ad_title = Session::get('UserProfile')['ad_title'];
		$userprofile->ad_type = Session::get('UserProfile')['ad_type'];
		//$userprofile->ad_expiry 		= $subscription_expiry;
		$userprofile->active_status = Session::get('UserProfile')['active_status'];
		$userprofile->ad_view_count = Session::get('UserProfile')['ad_view_count'];
		$userprofile->ads_validity = Session::get('UserProfile')['ads_validity'];
		$userprofile->description = Session::get('UserProfile')['description'];
		$userprofile->delete_status = Session::get('UserProfile')['delete_status'];
		$userprofile->status = Session::get('UserProfile')['status'];

		$userprofile->save();

		$ads_id = $userprofile->ad_id;

		$imageKeys = ['image1', 'image2', 'image3', 'image4', 'image5', 'image6', 'image7', 'image8'];

		foreach ($imageKeys as $key => $imageKey) {
			if (Session::has('ImageAd.' . $imageKey)) {
				AdPostingImage::create([
					'ads_id' => $ads_id,
					'image' => Session::get('ImageAd.' . $imageKey),
					'image_no' => $key + 1,
				]);
			}
		}

		$userprofile = new Propertyform;

		//	$userprofile->image 			= $adimage; 
		$userprofile->ads_id = $ads_id;
		$userprofile->user_id = Session::get('PropertyDetail')['user_id'];
		$userprofile->category_id = Session::get('PropertyDetail')['category_id'];
		$userprofile->sub_category_id = Session::get('PropertyDetail')['sub_category_id'];
		$userprofile->formtype = Session::get('PropertyDetail')['formtype'];
		$userprofile->fullname = Session::get('PropertyDetail')['fullname'];
		$userprofile->email = Session::get('PropertyDetail')['email'];
		$userprofile->mobile = Session::get('PropertyDetail')['mobile'];
		$userprofile->location = Session::get('PropertyDetail')['location'];
		$userprofile->state = Session::get('PropertyDetail')['state'];
		$userprofile->state_name = Session::get('PropertyDetail')['state_name'];
		$userprofile->city = Session::get('PropertyDetail')['city'];
		$userprofile->city_name = Session::get('PropertyDetail')['city_name'];
		$userprofile->neibourhood = Session::get('PropertyDetail')['neibourhood'];
		$userprofile->price = Session::get('PropertyDetail')['price'];
		$userprofile->property_type = Session::get('PropertyDetail')['property_type'];
		$userprofile->bedroom = Session::get('PropertyDetail')['bedroom'];
		$userprofile->bathroom = Session::get('PropertyDetail')['bathroom'];
		$userprofile->furnishing_status = Session::get('PropertyDetail')['furnishing_status'];
		$userprofile->construction_status = Session::get('PropertyDetail')['construction_status'];
		$userprofile->residence = Session::get('PropertyDetail')['residence'];
		$userprofile->listed_by = Session::get('PropertyDetail')['listed_by'];
		$userprofile->plot_type = Session::get('PropertyDetail')['plot_type'];
		$userprofile->price_mention = Session::get('PropertyDetail')['price_mention'];
		$userprofile->builtup_area = Session::get('PropertyDetail')['builtup_area'];
		$userprofile->carpet_area = Session::get('PropertyDetail')['carpet_area'];
		$userprofile->maintenance = Session::get('PropertyDetail')['maintenance'];
		$userprofile->total_floor = Session::get('PropertyDetail')['total_floor'];
		$userprofile->floor_no = Session::get('PropertyDetail')['floor_no'];
		$userprofile->car_parking = Session::get('PropertyDetail')['car_parking'];
		$userprofile->facing = Session::get('PropertyDetail')['facing'];
		$userprofile->project_name = Session::get('PropertyDetail')['project_name'];
		$userprofile->ad_title = Session::get('PropertyDetail')['ad_title'];
		$userprofile->ad_type = Session::get('PropertyDetail')['ad_type'];
		$userprofile->description = Session::get('PropertyDetail')['description'];
		$userprofile->delete_status = Session::get('PropertyDetail')['delete_status'];
		$userprofile->status = Session::get('PropertyDetail')['status'];
		$userprofile->save();

		$category_subscription_exists = DB::table('subscription_history')->where('category_id', Session::get('PropertyDetail')['category_id'])->where('user_id', Session::get('PropertyDetail')['user_id'])->exists();

		if ($category_subscription_exists) {
			$category_subscription_result = DB::table('subscription_history')->where('category_id', Session::get('PropertyDetail')['category_id'])->where('user_id', Session::get('PropertyDetail')['user_id'])->get();
			$used_ads = $category_subscription_result[0]->used_ads;
			$total_ads = $used_ads + 1;
			DB::table('subscription_history')->where('category_id', Session::get('PropertyDetail')['category_id'])->where('user_id', Session::get('PropertyDetail')['user_id'])->update(array('used_ads' => $total_ads));
		}

		Session::forget('UserProfile');
		Session::forget('PropertyDetail');
		Session::forget('SubscriptionData');
		Session::forget('ImageAd');
		Session::put('form-submitted', 'Form posted successfully. Thank You');

		return redirect("thank-you");
	}

	public function CommonPostSubmit()
	{
		$userprofile = new Adposting;
		$userprofile->ad_id = Session::get('UserProfile')['ad_id'];
		$userprofile->subscription_id = Session::get('SubscriptionData')['id'];
		$userprofile->user_id = Session::get('UserProfile')['user_id'];
		$userprofile->category_id = Session::get('UserProfile')['category_id'];
		$userprofile->image = Session::get('UserProfile')['image'];
		$userprofile->sub_category_id = Session::get('UserProfile')['sub_category_id'];
		$userprofile->formtype = Session::get('UserProfile')['formtype'];
		$userprofile->fullname = Session::get('UserProfile')['fullname'];
		$userprofile->email = Session::get('UserProfile')['email'];
		$userprofile->mobile = Session::get('UserProfile')['mobile'];
		$userprofile->location = Session::get('UserProfile')['location'];
		$userprofile->city = Session::get('UserProfile')['city'];
		$userprofile->price = Session::get('UserProfile')['price'];
		$userprofile->ad_title = Session::get('UserProfile')['ad_title'];
		$userprofile->ad_type = Session::get('UserProfile')['ad_type'];
		//$userprofile->ad_expiry 		= $subscription_expiry;
		$userprofile->active_status = Session::get('UserProfile')['active_status'];
		$userprofile->ad_view_count = Session::get('UserProfile')['ad_view_count'];
		$userprofile->ads_validity = Session::get('UserProfile')['ads_validity'];
		$userprofile->description = Session::get('UserProfile')['description'];
		$userprofile->delete_status = Session::get('UserProfile')['delete_status'];
		$userprofile->status = Session::get('UserProfile')['status'];

		$userprofile->save();

		$ads_id = $userprofile->ad_id;

		$imageKeys = ['image1', 'image2', 'image3', 'image4', 'image5', 'image6', 'image7', 'image8'];

		foreach ($imageKeys as $key => $imageKey) {
			if (Session::has('ImageAd.' . $imageKey)) {
				AdPostingImage::create([
					'ads_id' => $ads_id,
					'image' => Session::get('ImageAd.' . $imageKey),
					'image_no' => $key + 1,
				]);
			}
		}

		$userprofile = new Commonform;

		//	$userprofile->image 			= $adimage; 
		$userprofile->ads_id = $ads_id;
		$userprofile->user_id = Session::get('CommonDetail')['user_id'];
		$userprofile->category_id = Session::get('CommonDetail')['category_id'];
		$userprofile->sub_category_id = Session::get('CommonDetail')['sub_category_id'];
		$userprofile->formtype = Session::get('CommonDetail')['formtype'];
		$userprofile->fullname = Session::get('CommonDetail')['fullname'];
		$userprofile->email = Session::get('CommonDetail')['email'];
		$userprofile->mobile = Session::get('CommonDetail')['mobile'];
		$userprofile->location = Session::get('CommonDetail')['location'];
		$userprofile->state = Session::get('CommonDetail')['state'];
		$userprofile->state_name = Session::get('CommonDetail')['state_name'];
		$userprofile->city = Session::get('CommonDetail')['city'];
		$userprofile->city_name = Session::get('CommonDetail')['city_name'];
		$userprofile->neibourhood = Session::get('CommonDetail')['neibourhood'];
		$userprofile->price = Session::get('CommonDetail')['price'];
		$userprofile->ad_title = Session::get('CommonDetail')['ad_title'];
		$userprofile->ad_type = Session::get('CommonDetail')['ad_type'];
		$userprofile->description = Session::get('CommonDetail')['description'];
		$userprofile->delete_status = Session::get('CommonDetail')['delete_status'];
		$userprofile->status = Session::get('CommonDetail')['status'];
		$userprofile->save();

		$category_subscription_exists = DB::table('subscription_history')->where('category_id', Session::get('CommonDetail')['category_id'])->where('user_id', Session::get('CommonDetail')['user_id'])->exists();

		if ($category_subscription_exists) {
			$category_subscription_result = DB::table('subscription_history')->where('category_id', Session::get('CommonDetail')['category_id'])->where('user_id', Session::get('CommonDetail')['user_id'])->get();
			$used_ads = $category_subscription_result[0]->used_ads;
			$total_ads = $used_ads + 1;
			DB::table('subscription_history')->where('category_id', Session::get('CommonDetail')['category_id'])->where('user_id', Session::get('CommonDetail')['user_id'])->update(array('used_ads' => $total_ads));
		}

		Session::forget('UserProfile');
		Session::forget('CommonDetail');
		Session::forget('SubscriptionData');
		Session::forget('ImageAd');
		Session::put('form-submitted', 'Form posted successfully. Thank You');

		return redirect("thank-you");
	}

	public function user_ads_payment(Request $request)
	{
		$card = $request->card;
		$cvv = $request->cvv;
		$expiry_month = $request->expiry_month;
		$expiry_year = $request->expiry_year;
		$price = $request->price;
		$id = $request->id;
		$payment_method = $request->payment_method;
		$no_ads = $request->no_ads;

		$subscription = SubscriptionOrder::find($id);
		// $subscription = DB::table('subscription_orders')::where('id',$id)->first();
		$subscription->remaining_ads = $subscription->remaining_ads + $no_ads;
		$subscription->save();
		\Session::put('success', 'Ads Purchased Successfully.');
		return redirect("my-subscription");

	}

	public function getAdImages($adId)
	{
		$images = AdPostingImage::where('ads_id', $adId)->get(['image as url']);

		$images = $images->map(function ($image) {
			$path = $image->url;
			return [
				'url' => $image->url,
				// 'width' => getimagesize($path)[0],
				// 'height' => getimagesize($path)[1],
			];
		});

		return response()->json($images);
	}

	public function expiry_data(Request $request)
	{
		$no = '30';
		$dates = date("y-m-d");
		$date = date_create($dates);
		date_add($date, date_interval_create_from_date_string($no . "days"));
		echo date_format($date, "Y-m-d");
	}

	public function add_expire(Request $request)
	{
		$currentdate = date("d-m-Y");
		$update = DB::table('ads_postings')->where('ad_expiry', $currentdate)->update(
			array('status' => '3')
		);

		if ($update) {
			echo 'updated';
		} else {
			echo 'no expiry today';
		}
	}

	public function subscription_expire(Request $request)
	{
		$currentdate = date("d-m-Y");
		$subscription_orders = DB::table('subscription_orders')->where('subscription_expiry', $currentdate)->get();
		foreach ($subscription_orders as $row) {
			$user_id = $row->user_id;
			$category_id = $row->category_id;

			$ads_postings_exists = DB::table('ads_postings')->where('user_id', $user_id)->where('category_id', $category_id)->where('active_status', '0')->exists();

			if ($ads_postings_exists) {
				$update = DB::table('ads_postings')->where('user_id', $user_id)->where('category_id', $category_id)->where('active_status', '0')->update(array('status' => '3'));
			}
		}
	}

	public function user_signup(Request $request)
	{

		date_default_timezone_set("Asia/Kolkata");
		$validator = Validator::make($request->all(), [
			'name' => 'required|max:50|min:0',
			'email' => 'required|email|unique:customers,email',
			'password' => 'required|max:50|min:0',
			'mobile' => 'required',
			'country' => 'required|max:50|min:0',
			'state' => 'required|max:50|min:0',
			'city' => 'required|max:50|min:0',
			'pin' => 'required|max:50|min:0',
		]);
		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator)->withInput();
		}
		$prefix = "Post-00001";
		$users = DB::table('customers')->orderBy('id', 'DESC')->first();
		if (!empty($users)) {
			$referral_code = $users->id + 1;
		} else {
			$referral_code = 1;
		}
		if (!$request->isValid) {
			\Session::put('error', 'First verify your phone number..');
			return redirect("login");
		}
		$referCustomerExist = Customer::with('subscriptionhistory')->where('referral_code', $request->referralto)->exists();
		$customermbed = Customer::withTrashed()->where('email', $request->email)->where('mobile', $request->mobile)->where('delete_status', 1)->first();
		if ($customermbed) {
			\Session::put('error', 'Your Account has been De-activate.');
			return redirect("login");
		}
		$customermbe = Customer::withTrashed()->where('email', $request->email)->where('mobile', $request->mobile)->first();
		if ($customermbe) {
			\Session::put('error', 'Already Exists Mobile Number And Email Id');
			return redirect("login");
		}
		$customere = Customer::withTrashed()->where('email', $request->email)->first();
		if ($customere) {
			\Session::put('error', 'Already Exists Email Id');
			return redirect("login");
		}
		$customerm = Customer::withTrashed()->where('mobile', $request->mobile)->first();
		if ($customerm) {
			\Session::put('error', 'Already Exists Mobile Number');
			return redirect("login");
		}

		if (!$referCustomerExist && isset($request->referralto)) {
			\Session::put('error', 'You entered the wrong referral code.');
			return redirect("login");
		}

		if ($referCustomerExist) {
			$user_ref_id = Customer::with('subscriptionhistory')->where('referral_code', $request->referralto)->first();
		}
		$adminsetting = Adminsettings::first();
		if ($referCustomerExist && $adminsetting->is_active_ad_referral) {
			$subscription = SubscriptionHistory::where('user_id', $user_ref_id->id)->where('type', '!=', 'Normal')->whereDate('subscription_expiry', '>=', Carbon::now())->exists();
			//$userAdsExist = Adposting::where('user_id',$user_ref_id->id)->where('active_status',1)->where('status',1)->where('delete_status',0)->exists();
		} else {
			$subscription = true;
		}
		$reserve_expiry_at = (new DateTime())->modify('+' . $adminsetting->reserve_expiry_timeline . ' days')->format('Y-m-d');
		$userprofile = new Customer();
		$referral_code = str_pad($referral_code, 4, '0', STR_PAD_LEFT);
		$namePart = substr($request->name, 0, 4);
		$mobilePart = substr($request->mobile, -4);
		$user_id = $namePart . $mobilePart;
		$userprofile->referral_code = $user_id;
		if ($referCustomerExist && $subscription) {
			$userprofile->referralto = $request->referralto ?? null;
		}
		$userprofile->name = $request->name;
		if ($referCustomerExist && $subscription) {
			$userprofile->parent_id = $user_ref_id->id ?? null;
		}
		$userprofile->email = $request->email;
		$userprofile->password = Hash::make($request->password);
		$userprofile->mobile = $request->mobile;
		$userprofile->user_type = 'Free';
		$userprofile->country = $request->country;
		$userprofile->state = $request->state;
		$userprofile->city = $request->city;
		$userprofile->wallet_bonus = $adminsetting->welcome_amount;
		$userprofile->pin = $request->pin;
		if ($referCustomerExist && $subscription) {
			$userprofile->reserve_expiry_at = $reserve_expiry_at;
		}
		$userprofile->membership_expiry_at = date('Y-m-d', strtotime(date('d-m-Y H:i:s') . ' + ' . $adminsetting->reserve_member_expiry . ' days'));
		$userprofile->datetime = date('Y-m-d H:i:s');
		$userprofile->no_of_ads = '0';
		$userprofile->member_id = 'WP' . date('Y') . rand(1000, 9999);
		$userprofile->delete_status = '0';
		$userprofile->status = '0';
		$userprofile->save();
		if ($referCustomerExist && $subscription) {
			// Retrieve the subscription
			$subscription = $user_ref_id->subscriptionhistory()
				->whereDate('subscription_expiry', '>=', Carbon::now())
				->orderBy('created_at', 'desc')
				->first();


			$subscription_id = $subscription ? $subscription->id : null;

			// Create the Customer_child record
			Customer_child::create([
				'user_id' => $user_ref_id->id,
				'child_id' => $userprofile->id,
				'subscription_id' => $subscription_id,
				'joining_date' => date('Y-m-d'),
				'reserve_expiry_at' => $reserve_expiry_at,
				'status' => 'Active',
			]);
			$event = DefaultNotification::where('event', 'new_seeding')->first();
			if (!empty($event)) {
				$title = $event->title;
				$content = $event->content;
				$body = str_replace("#member_id", $userprofile->member_id, $content);
				$notifyArray = array(
					'user_id' => $user_ref_id->id,
					'event_name' => $event->event,
					'title' => $title,
					'body' => $body,
				);

				$this->singleUserNotification($notifyArray);
			}
		}


		// $childcust   = PrimeUser::where('remaining_child_count',$user_id)->first();
		// $child = new Customer_child();
		// $child->user_id  = $userprofile->id;
		// $userprofile->save();

		/*	$customerOTP = OTP::where('mobile',$userprofile->mobile)->first();
			$customerOTP->customer_id = $userprofile->id;
			$customerOTP->save();  */ /***temp comment **/
		$token = Str::random(64);
		CustomerVerify::create([
			'customer_id' => $userprofile->id,
			'token' => $token
		]);

		$mailData = ['token' => $token];
		$mailContent = Mail::to($request->email)->send(new EmailVerificationEmail($mailData));

		/****************New Added Admin email *****/
		$userStateName = States::where('id', $request->state)->first();

		$userCityName = City::where('id', $request->city)->first();
		$customerEmailDetail = array(
			'name' => $userprofile->name,
			'password' => $request->password,
			'email' => $userprofile->email,
			'mobile' => $userprofile->mobile,
			'member_id' => $userprofile->member_id,
			'pin' => $userprofile->pin,
			'state' => $userStateName->name ?? "",
			'city' => $userCityName->name ?? "",
			'country' => 'India',
		);
		$messagead = '';
		Mail::send('email.new-user-register', $customerEmailDetail, function ($messagead) use ($customerEmailDetail) {
			$messagead->to('choudharyfaizasif@gmail.com', 'Welcome Post')->subject('New User Registered on Welcome Post');
			$messagead->from($customerEmailDetail['email'], $customerEmailDetail['name']);
		});

		if ($adminsetting->welcome_amount > 0) {
			$walletamout = new WalletAmout();
			$walletamout->amount = $userprofile->wallet_bonus;
			$walletamout->userid = $userprofile->id;
			$walletamout->status = "3";
			$walletamout->description = "Welcome bonus credited to your wallet of about ₹" . $adminsetting->welcome_amount;
			$walletamout->datetime = date("d/m/y/ h:i:s A");
			$walletamout->save();
			$welcomeAmount = $adminsetting->welcome_amount;

			$event = DefaultNotification::where('event', 'wallet_credit')->first();
			if (!empty($event)) {
				$title = $event->title;
				$content = $event->content;
				$body = str_replace("#amount", $userprofile->wallet_bonus, $content);
				$notifyArray = array(
					'user_id' => $userprofile->id,
					'event_name' => $event->event,
					'title' => $title,
					'body' => $body,
				);

				$this->singleUserNotification($notifyArray);
			}

			\Session::put('success', 'Check and verify your email');
			return redirect("login")->with('welcomeAmount', $welcomeAmount);
		}
		\Session::put('success', 'Verification Email sent, Please check your email in inbox, spam and junk folder.');
		return redirect("login");
	}

	public function verifyAccount($token)
	{
		$verifyUser = CustomerVerify::where('token', $token)->first();

		// echo "<pre/>"; print_r($verifyUser); die('sjbfvkjber');
		$message = 'Sorry your email cannot be identified.';

		if (!is_null($verifyUser)) {
			$user = $verifyUser->customer;

			if (!$user->is_email_verified) {
				$verifyUser->customer->is_email_verified = 1;
				$verifyUser->customer->save();
				$message = "Your e-mail is verified. You can now login.";
			} else {
				$message = "Your e-mail is already verified. You can now login.";
			}
		}

		\Session::put('success', $message);
		return redirect(url('login'));

	}

	public function getusername($id)
	{
		$data = DB::table('customers')->where('referral_code', $id)->first();
		$adminsetting = Adminsettings::first();
		if (isset($data)) {
			$subscription = SubscriptionHistory::where('user_id', $data->id)->where('type', '!=', 'Normal')->whereDate('subscription_expiry', '>=', Carbon::now())->exists();
			//$adsExist = Adposting::where('user_id',$data->id)->where('active_status',1)->where('status',1)->where('delete_status',0)->exists();
		}
		if (isset($subscription) && !$subscription && $adminsetting->is_active_ad_referral) {
			return response()->json(['status' => '3']);
		}
		if (!empty($data)) {
			return response()->json(['status' => 1, 'name' => $data->name]);
		} else {
			return response()->json(['status' => '2']);
		}
	}


	public function user_login(Request $request)
	{
		// dd($request->all());
		$validator = Validator::make($request->all(), [
			'email' => 'required|max:50|min:0',
			'password' => 'required|max:50|min:0',
		]);

		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator)->withInput();
		}

		$email = $request->email;
		$password = $request->password;
		$result = Customer::withTrashed()->where('email', $email)->first();

		if (isset($result)) {
			if ($result->delete_status == 1 && isset($result->deleted_at)) {
				\Session::put('error', 'Your account has been deleted, you can contact us for restoration of it with in 30 Days.');
				return redirect(url('login'));
			}
			$result_cus = DB::table('customers')->where('email', $email)->where('delete_status', 0)->where('status', 0)->get();

			if (count($result_cus) != 0) {
				$result_cus1 = Customer::where('email', $email)->first();
				$loginAttempt = LoginAttempt::firstOrNew(['user_id' => $result_cus1->id]);
				if ($loginAttempt->is_account_locked) {
					\Session::put('loginAttepmt', '1');
					return redirect(url('login'));
				}
				if (!Hash::check($request->password, $result_cus1->password)) {
					$loginAttempt->increment('attempt_count');

					if ($loginAttempt->attempt_count >= self::MAX_ATTEMPTS) {
						$loginAttempt->is_account_locked = true;
						\Session::put('loginAttepmt', '1');
					}

					$loginAttempt->last_login = now();
					$loginAttempt->save();
					\Session::put('error', 'Invalid Credentials, You have only ' . (self::MAX_ATTEMPTS - $loginAttempt->attempt_count) . ' attempts left');
					return redirect(url('login'));
				}



				$stu_id = $result_cus[0]->id;
				$no = 30;
				$dates = $result_cus[0]->deleted_at;
				$date = date_create($dates);
				date_add($date, date_interval_create_from_date_string($no . "days"));

				$today_date = date("d-m-Y");
				$diff = $this->dateDiffInDays($today_date, date_format($date, "d-m-Y"));

				if ($diff <= 30) {
					if ($result_cus[0]->is_email_verified == 1) {
						$cust = $request->session()->put('id', $stu_id);

						if (session()->has('id')) {
							$customer = Customer::findOrFail($stu_id);
							if ($customer->wallet_bonus >= 10) {
								session()->put('welcomeAmount', $customer->wallet_bonus);
							}
							$loginAttempt->attempt_count = 0;
							$loginAttempt->last_login = now();
							$loginAttempt->save();
						}
						return redirect(url('/'));
					} else {
						\Session::put('verifyCheck', '1');
						\Session::put('error', 'Your Email Account is not verified. Please Verify Your Account.');
						return redirect(url('login'));
					}


				} else {
					\Session::put('error', 'Your Account has been permanently Closed.');
					return redirect(url('login'));
				}
			} else {
				\Session::put('error', 'Your Account has been De-activated.');
				return redirect(url('login'));
			}

		} else {
			\Session::put('error', 'Invalid Credentials');
			return redirect(url('login'));
		}
	}

	public function login_with_mobile(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'mobile' => 'required|max:50|min:0',
			'password' => 'required|max:50|min:0',
		]);

		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator)->withInput();
		}

		$mobile = $request->mobile;
		$password = $request->password;
		$result = Customer::withTrashed()->where('mobile', $mobile)->exists();

		if ($result) {
			if (isset($result->deleted_at) && $result->delete_status == 1) {
				\Session::put('error', 'Your account has been deleted, you can contact us for restoration of it with in 30 Days.');
				return redirect(url('login'));
			}
			$result_cus = DB::table('customers')->where('mobile', $mobile)->where('delete_status', 0)->where('status', 0)->get();

			if (count($result_cus) != 0) {
				$result_cus1 = DB::table('customers')->where('mobile', $mobile)->first();
				$loginAttempt = LoginAttempt::firstOrNew(['user_id' => $result_cus1->id]);
				if ($loginAttempt->is_account_locked) {
					\Session::put('loginAttepmt', '1');
					return redirect(url('login'));
				}
				if (!Hash::check($request->password, $result_cus1->password)) {
					$loginAttempt->increment('attempt_count');

					if ($loginAttempt->attempt_count >= self::MAX_ATTEMPTS) {
						$loginAttempt->is_account_locked = true;
					}

					$loginAttempt->last_login = now();
					$loginAttempt->save();
					\Session::put('error', 'Invalid Credentials, You have only ' . (self::MAX_ATTEMPTS - $loginAttempt->attempt_count) . ' attempts left');
					return redirect(url('login'));
				}
				$stu_id = $result_cus[0]->id;
				$no = 30;
				$dates = $result_cus[0]->deleted_at;
				$date = date_create($dates);
				date_add($date, date_interval_create_from_date_string($no . "days"));

				$today_date = date("d-m-Y");
				$diff = $this->dateDiffInDays($today_date, date_format($date, "d-m-Y"));

				if ($diff <= 30) {
					if ($result_cus[0]->is_email_verified == 1) {
						$request->session()->put('id', $stu_id);
						return redirect(url('/'));
						//return redirect(url('post-ads'));
					} else {
						\Session::put('verifyCheck', '1');
						\Session::put('error', 'Your Email Account is not verified. Please Verify Your Account.');
						return redirect(url('login'));
					}


				} else {
					\Session::put('error', 'Your Account has been permanently Closed.');
					return redirect(url('login'));
				}
			} else {
				\Session::put('error', 'Your Account has been De-activate.');
				return redirect(url('login'));
			}

		} else {
			\Session::put('error', 'Invalid Credentials');
			return redirect(url('login'));
		}
	}

	public function sendOTP(Request $request)
	{
		// Generate a six-digit OTP
		$validator = Validator::make($request->all(), [
			'mobile' => 'required|max:10|min:10|unique:customers,mobile',
		]);
		if ($validator->fails()) {
			return response()->json([
				'success' => false,
				'error' => $validator->errors()->first('mobile')
			], 422);
		}
		$otp = rand(100000, 999999);
		$mobile_number = $request->mobile;


		// Assuming you have a model named OTP for managing OTPs
		OTP::create([
			'mobile' => $mobile_number,
			'otp' => $otp,
			'expiry' => now()->addMinutes(10),
		]);

		$message = "$otp is the One Time Password(OTP) to verify your MOB number at Web Mingo, This OTP is Usable only once and is valid for 10 min,PLS DO NOT SHARE THE OTP WITH ANYONE";
		$dlt_id = '1307161465983326774';
		$request_parameter = array(
			'authkey' => '133780AZGqc6gKWfh63da1812P1',
			'mobiles' => $mobile_number,
			'message' => urlencode($message),
			'sender' => 'WMINGO',
			'route' => '4',
			'country' => '91',
			'unicode' => '1',
		);
		$url = "http://sms.webmingo.in/api/sendhttp.php?";
		foreach ($request_parameter as $key => $val) {
			$url .= $key . '=' . $val . '&';
		}
		$url = $url . 'DLT_TE_ID=' . $dlt_id;
		$url = rtrim($url, "&");
		try {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			//get response
			$output = curl_exec($ch);
			curl_close($ch);
			return response()->json([
				'success' => true,
				'message' => 'Otp Successfully Send on Your mobile number!',
			]);
			// return true;
		} catch (\Exception $e) {
			dd($e->getMessage());
		}
	}

	public function verifyOTP(Request $request)
	{
		$mobile = $request->mobile;
		$otp = $request->otp;

		$isValid = OTP::verifyOTP($mobile, $otp);
		if ($isValid) {
			return response()->json(['success' => true, 'isValid' => $isValid]);
		} else {
			return response()->json(['success' => false, 'isValid' => $isValid]);
		}

	}

	public function sendOTPChange(Request $request)
	{
		// Validate input
		$validator = Validator::make($request->all(), [
			'mobile' => 'required|digits:10|unique:customers,mobile',
			'currentPassword' => 'required',
		]);

		if ($validator->fails()) {
			return response()->json([
				'success' => false,
				'message' => $validator->errors()->first(),
			], 400);
		}

		// Check if the provided password is correct
		$customer = Customer::findOrFail($request->userId);
		if (!Hash::check($request->currentPassword, $customer->password)) {
			return response()->json([
				'success' => false,
				'message' => 'Current password is incorrect.',
			], 400);
		}

		// Generate a six-digit OTP
		$otp = rand(100000, 999999);
		$mobile_number = $request->mobile;

		// Assuming you have a model named OTP for managing OTPs
		OTP::create([
			'mobile' => $mobile_number,
			'otp' => $otp,
			'expiry' => now()->addMinutes(10),
		]);

		$message = "$otp is the One Time Password(OTP) to verify your MOB number at Web Mingo, This OTP is Usable only once and is valid for 10 min,PLS DO NOT SHARE THE OTP WITH ANYONE";
		$dlt_id = '1307161465983326774';
		$request_parameter = array(
			'authkey' => '133780AZGqc6gKWfh63da1812P1',
			'mobiles' => $mobile_number,
			'message' => urlencode($message),
			'sender' => 'WMINGO',
			'route' => '4',
			'country' => '91',
			'unicode' => '1',
		);
		$url = "http://sms.webmingo.in/api/sendhttp.php?";
		foreach ($request_parameter as $key => $val) {
			$url .= $key . '=' . $val . '&';
		}
		$url = $url . 'DLT_TE_ID=' . $dlt_id;
		$url = rtrim($url, "&");
		try {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			//get response
			$output = curl_exec($ch);
			curl_close($ch);
			return response()->json([
				'success' => true,
				'message' => 'Otp Successfully Send on Your mobile number!',
			]);
		} catch (\Exception $e) {
			return response()->json([
				'success' => false,
				'message' => 'Failed to send OTP. Please try again.',
			], 500);
		}
	}


	public function verifyChangeMobileOTP(Request $request)
	{
		$mobile = $request->mobile;
		$otp = $request->otp;
		$userId = $request->userId;

		$isValid = OTP::verifyOTP($mobile, $otp);
		if ($isValid) {
			$customer = Customer::findOrFail($userId);
			$customer->mobile = $mobile;
			$customer->save();
			return response()->json(['success' => true, 'isValid' => $isValid]);
		} else {
			return response()->json(['success' => false, 'isValid' => $isValid]);
		}

	}


	function dateDiffInDays($date1, $date2)
	{
		// Calculating the difference in timestamps
		$diff = strtotime($date2) - strtotime($date1);

		// 1 day = 24 hours
		// 24 * 60 * 60 = 86400 seconds
		return abs(round($diff / 86400));
	}

	public function get_state(Request $request)
	{
		$id = $request->country;
		$states = DB::table('states')->where('country_id', $id)->get();

		echo '<option value=""> ----------------- Select ------------- </option>';
		foreach ($states as $row) {
			echo '<option value=' . $row->id . '>' . $row->name . '</option>';
		}
	}

	public function get_city(Request $request)
	{
		$id = $request->state_id;
		$City = DB::table('cities')->where('state_id', $id)->get();
		$response = '<option value="">Select City </option>';
		foreach ($City as $row) {
			$response .= '<option value=' . $row->id . '>' . $row->name . '</option>';
		}
		return response()->json($response);
	}

	public function cities_by_state(Request $request)
	{
		$id = $request->state_id;
		$city = DB::table('cities')->where('state_id', $id)->get();
		//dd($city);
		if (isset($city)) {
			$response = '<option value="">Select City </option>';
			foreach ($city as $row) {
				$response .= '<option value=' . $row->id . '>' . $row->name . '</option>';
			}
		} else {
			$response .= '<option value="">No City Found </option>';
		}

		return response()->json($response);
	}

	public function post_chat_message(Request $request)
	{
		$messagepost = $request->messagepost;
		$consumer_id = $request->consumer_id;
		$session_user_id = $request->user_id;
		$reciever_id2 = $request->reciever_id;
		$is_admin_chat = $request->isAdminChat;
		$admin = $request->admin;

		if ($messagepost == '') {
			echo 'Please enter some message';
		} else {
			$id = DB::table('chat_messages')->insertGetId([
				'user_id' => $request->user_id,
				'consumer_id' => $request->consumer_id,
				'reciever_id' => $request->consumer_id,
				'sender_id' => $request->user_id,
				'topic' => $request->messagepost,
				'isAdminChat' => $is_admin_chat,
				'is_read' => '0'
			]);
			$customerX = Customer::where('id', $request->reciever_id)->first();

			if ($customerX->fcm_token && $is_admin_chat) {
				$title = 'Helpdesk!';
				$body = 'Message: ' . $request->messagepost;
				$image = null;
				$response = $this->sendNotification($title, $body, $customerX->fcm_token, $image);
			}

			DB::table('chat_room')->where('user_id', $consumer_id)->where('sender_id', $session_user_id)->update(['clear_chat' => '0']);

			// Fetch chat messages
			$chat_messages = DB::select(DB::raw("SELECT * FROM chat_messages WHERE user_id = '$session_user_id' AND consumer_id = '$consumer_id' OR user_id = '$consumer_id' AND consumer_id = '$session_user_id' "));

			// Output chat messages in the desired format
			foreach ($chat_messages as $row) {
				$resultPro = DB::table('customers')->where('id', $row->user_id)->get();
				if (!isset($admin) && $admin != 1) {
					?>
									<div class="<?php echo ($row->user_id == $session_user_id) ? 'chat-message-right' : 'chat-message-left'; ?> pb-4">
										<div>
											<img src="<?php echo $resultPro[0]->image; ?>" class="rounded-circle mr-1" alt="<?php echo $resultPro[0]->name; ?>" width="40" height="40">
											<div class="text-muted small text-nowrap mt-2"><?php echo $resultPro[0]->name; ?></div>
										</div>
										<div class="flex-shrink-1 bg-light rounded py-2 px-3 mr-3">
											<div class="font-weight-bold mb-1"><?php echo $row->topic; ?></div>
											<?php
											$created_at = date_create($row->created_at);
											$created_date = date_format($created_at, "d-m-Y");
											$created_time = date_format($created_at, "H:i A");
											?>
											<p style="margin-top:10px;"><?php echo $created_date; ?> | <?php echo $created_time; ?></p>
										</div>
									</div>
								<?php } else {
					$subs_date = explode(" ", $row->created_at);
					$dats_subs = date_create($subs_date[0]);
					$time_subs = date_create($subs_date[1]);

					if ($session_user_id == $row->reciever_id) {
						?>
												<div class="chat-text" style="margin-top:20px;">
													<span class="message"><?php echo $row->topic; ?></span>
													<p style="margin-top:10px;"><?php echo date_format($dats_subs, "d-m-Y"); ?> | <?php echo date_format($time_subs, "H:i A"); ?></p>
												</div>
												<?php
					} else {
						?>
												<div class="chat-text right" style="margin-top:20px;">
													<span class="message"><?php echo $row->topic; ?></span>
													<p style="margin-left: 444px; margin-top:10px;"><?php echo date_format($dats_subs, "d-m-Y"); ?> | <?php echo date_format($time_subs, "H:i A"); ?></p>
												</div>
						
									<?php }
				} ?>
							<?php
			}
		}
	}

	public function get_chat(Request $request)
	{
		$consumer_id = $request->consumer_id;
		$session_user_id = Session::get('id');
		$reciever_id2 = $request->reciever_id;
		$is_admin_chat = $request->isAdminChat;
		$admin = $request->admin;

		$chat_messages = DB::select(DB::raw("SELECT * FROM chat_messages WHERE (user_id = '$session_user_id' AND consumer_id = '$consumer_id') OR (user_id = '$consumer_id' AND consumer_id = '$session_user_id')"));

		foreach ($chat_messages as $row) {
			$resultPro = DB::table('customers')->where('id', $row->user_id)->get();
			if (!isset($admin) && $admin != 1) {
				?>
								<div class="<?php echo ($row->user_id == $session_user_id) ? 'chat-message-right' : 'chat-message-left'; ?> pb-4">
									<div>
										<img src="<?php echo $resultPro[0]->image; ?>" class="rounded-circle mr-1" alt="<?php echo $resultPro[0]->name; ?>" width="40" height="40">
										<div class="text-muted small text-nowrap mt-2"><?php echo $resultPro[0]->name; ?></div>
									</div>
									<div class="flex-shrink-1 bg-light rounded py-2 px-3 mr-3">
										<div class="font-weight-bold mb-1"><?php echo $row->topic; ?></div>
										<?php
										$created_at = date_create($row->created_at);
										$created_date = date_format($created_at, "d-m-Y");
										$created_time = date_format($created_at, "H:i A");
										?>
										<p style="margin-top:10px;"><?php echo $created_date; ?> | <?php echo $created_time; ?></p>
									</div>
								</div>
								<?php
			} else {
				$subs_date = explode(" ", $row->created_at);
				$dats_subs = date_create($subs_date[0]);
				$time_subs = date_create($subs_date[1]);

				if ($session_user_id == $row->reciever_id) {
					?>
										<div class="chat-text" style="margin-top:20px;">
											<span class="message"><?php echo $row->topic; ?></span>
											<p style="margin-top:10px;"><?php echo date_format($dats_subs, "d-m-Y"); ?> | <?php echo date_format($time_subs, "H:i A"); ?></p>
										</div>
										<?php
				} else {
					?>
										<div class="chat-text right" style="margin-top:20px;">
											<span class="message"><?php echo $row->topic; ?></span>
											<p style="margin-left: 444px; margin-top:10px;"><?php echo date_format($dats_subs, "d-m-Y"); ?> | <?php echo date_format($time_subs, "H:i A"); ?></p>
										</div>
										<?php
				}
			}
		}
	}



	public function get_location(Request $request)
	{
		$id = $request->city_id;
		$location = DB::table('locations')->where('city_id', $id)->get();
		$html = '<option value="">Select Neighbourhood </option>';
		foreach ($location as $row) {
			$html .= '<option value=' . $row->id . '>' . $row->location . '</option>';
		}
		return response()->json($html);
	}

	public function send_enquiry(Request $request)
	{
		$enquiry_name = $request->input('enquiry_name');
		$enquiry_email = $request->input('enquiry_email');
		$enquiry_mobile = $request->input('enquiry_mobile');
		$enquiry_category = $request->input('enquiry_category');
		$enquiry_message = $request->input('enquiry_message');

		Enquiry::create([
			'enquiry_name' => $enquiry_name,
			'enquiry_email' => $enquiry_email,
			'enquiry_mobile' => $enquiry_mobile,
			'enquiry_category' => $enquiry_category,
			'enquiry_message' => $enquiry_message
		]);


		return redirect("/");

	}

	public function forgot_process(Request $request)
	{
		$email = $request->email_id;

		$customer = Customer::where('email', $email)->where('delete_status', 0)->first();

		if (isset($customer)) {
			$data['id'] = $customer->id;
			$data['city'] = City::where('delete_status', '0')->get();
			return view('website.forget-password', $data);
		} else {
			\Session::put('error', 'Invalid Credentials');
			return redirect(url('login'));
		}

	}

	public function new_pswd(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'password' => 'required',
			'confirm_password' => 'required|min:8|confirmed',
		]);

		if ($request->password == $request->confirm_password) {
			$id = $request->customer_id;

			$customer = Customer::find($id);

			if (isset($customer)) {
				$customer->password = $request->password;
				$customer->save();
				$loginAttempt = LoginAttempt::where('user_id', $customer->id)->first();
				$loginAttempt->is_account_locked = false;
				$loginAttempt->save();
				\Session::put('success', 'Password Change Successfull.');
				return redirect('login');

			} else {
				\Session::put('error', 'Invalid User');
				return redirect('login');
			}
		} else {
			\Session::put('error', "Password and Confirm paassword doesn't match");
			return redirect('login');
		}
	}

	public function top_search(Request $request)
	{
		$filter_search = $request->search;

		$allpost = Adposting::where('ad_title', 'like', '%' . $filter_search . '%')

			->where('ad_expiry', '!=', NULL)->whereDate('ad_expiry', '>', date('Y-m-d'))->where('delete_status', '0')->get();

		$response = '';
		if (isset($allpost)) {
			foreach ($allpost as $orderDetail) {
				$category = Categories::find($orderDetail->category_id);
				$response .= '<li class="searchlisting" search-value="' . $filter_search . '">
                                    <div>
                                        <h4>' . ucfirst($orderDetail->ad_title) . '</h4>
                                        <span>' . ucfirst($category->name) . '</span>
                                        <a href="https://welcomepost.in/ads-details/' . $orderDetail->id . '"></a>
                                    </div>
                                </li>';
			}

		} else {
			$response .= '<option value="">No Record Found </option>';
		}
		return response()->json($response);
	}

	public function truncate()
	{
		DB::table('chat_messages')->delete();
		/*DB::table('ads_postings')->delete();
		DB::table('jobforms')->delete();
		DB::table('mobileforms')->delete();
		DB::table('propertyforms')->delete();
		DB::table('vehicleforms')->delete();
		DB::table('commonforms')->delete();
		DB::table('categories')->delete();
		DB::table('subcategories')->delete();
		DB::table('ads_enquiries')->delete();*/
	}

	public function profile(Request $request, $id)
	{
		$data['page'] = 'Profile';
		$data['PremiumCategories'] = Categories::where('delete_status', '0')->where('premium', '1')->orderby('id', 'desc')->get();
		$data['TopCategories'] = Categories::where('delete_status', '0')->where('top', '1')->orderby('id', 'desc')->get();
		$data['TrendingCategories'] = Categories::where('delete_status', '0')->where('trending', '1')->orderby('id', 'desc')->get();
		$data['allcategories'] = Categories::where('delete_status', '0')->orderby('name', 'ASC')->get();
		$data['Pages'] = Pages::where('delete_status', '0')->get();
		$data['city'] = City::where('delete_status', '0')->orderby('name', 'asc')->get();
		$userIp = $request->ip();
		$data['locationinfo'] = \Location::get($userIp);
		$data['customerinfo'] = Customer::with('countries', 'states', 'cities')->find($id);

		$data['allads'] = DB::table('ads_postings')->where('user_id', $id)->where('active_status', '1')->where('status', '1')->get();
		return view('website.customer.profile', $data);
	}

	public function user_logout()
	{
		Auth::logout();
		Session::flush();
		return redirect(url('login'));
	}


	public function check_parent($user_id)
	{
		$user = Customer::where('id', $user_id)->whereNotNull('parent_id')->whereDate('reserve_expiry_at', '>=', date('Y-m-d'))->first();
		if (!empty($user)) {
			if ($user->parent_id != '') {
				return $user;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}



}