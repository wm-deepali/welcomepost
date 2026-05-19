<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Categories;
use App\Models\Subcategories;
use App\Models\States;
use App\Models\City;
use App\Models\Job;
use App\Models\Construction;
use App\Models\Furnishing;
use App\Models\Brand;
use App\Models\Vehicletypes;
use App\Models\Fueltype;
use App\Models\Transmission;
use App\Models\Residence;
use App\Models\Facing;
use Validator;
use DB;
use Mail;
use Session;
use ImageOptimizer;
use App\Models\AdPosting;
use App\Models\AdPostingImage;
use App\Models\Jobforms;
use App\Models\Mobileform;
use App\Models\Vehicleform;
use App\Models\Propertyform;
use App\Models\DefaultNotification;
use App\Models\Subscription;
use App\Models\Commonform;
use App\Models\SubscriptionHistory;
use Illuminate\Support\Str;
use App\Models\CustomerVerify;
use App\Mail\EmailVerificationEmail;

class AdsController extends Controller
{
    public function index()
    {
        $data['pending'] = Adposting::where('delete_status', '0')->where('status', '0')->get();
        $data['published'] = Adposting::where('delete_status', '0')->where('status', '1')->get();
        $data['rejected'] = Adposting::where('delete_status', '0')->where('status', '2')->get();
        return view('admin.ads.index', $data);
    }

    public function viewAdminAd($id)
    {
        $ad = DB::table('ads_postings')
            ->where('id', $id)
            ->first();

        if (!$ad) {
            abort(404);
        }

        $images = DB::table('ads_posting_images')
            ->where('ads_id', $ad->ad_id)
            ->get();

        $category = DB::table('categories')
            ->where('id', $ad->category_id)
            ->first();

        $subcategory = DB::table('subcategories')
            ->where('id', $ad->sub_category_id)
            ->first();

        $customer = DB::table('customers')
            ->where('id', $ad->user_id)
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Get Dynamic Form Data
        |--------------------------------------------------------------------------
        */

        $formData = null;

        if ($ad->formtype == 1) {

            $formData = Jobforms::where('ads_id', $ad->ad_id)->first();

        } elseif ($ad->formtype == 2) {

            $formData = Mobileform::where('ads_id', $ad->ad_id)->first();

        } elseif ($ad->formtype == 3) {

            $formData = Vehicleform::where('ads_id', $ad->ad_id)->first();

        } elseif ($ad->formtype == 4) {
            $formData = Propertyform::where('ads_id', $ad->ad_id)->first();

        } else {

            $formData = Commonform::where('ads_id', $ad->ad_id)->first();
        }

        return view(
            'admin.ads.view-ad',
            compact(
                'ad',
                'images',
                'category',
                'subcategory',
                'customer',
                'formData'
            )
        );
    }

    public function adminPostAdsUser()
    {
        $data['states'] = States::where('delete_status', 0)
            ->orderBy('name', 'ASC')
            ->get();

        $data['allcategories'] = Categories::where('delete_status', '0')
            ->orderBy('name', 'ASC')
            ->get();

        return view('admin.ads.post_ads', $data);
    }

    /*
    |--------------------------------------------------------------------------
    | Select Customer Page
    |--------------------------------------------------------------------------
    */

    public function findCustomer(Request $request)
    {
        $request->validate([

            'mobile' => [
                'nullable',
                'regex:/^[6-9]\d{9}$/',
                'required_without:email'
            ],

            'email' => [
                'nullable',
                'email',
                'required_without:mobile'
            ]

        ], [

            'mobile.regex' => 'Please enter a valid Indian mobile number.',
            'mobile.required_without' => 'Mobile or email is required.',

            'email.email' => 'Please enter a valid email address.',
            'email.required_without' => 'Email or mobile is required.'

        ]);

        $customer = null;

        // Check by mobile first
        if (!empty($request->mobile)) {

            $customer = Customer::where('mobile', $request->mobile)->first();
        }

        // If not found, then check by email
        if (!$customer && !empty($request->email)) {

            $customer = Customer::where('email', $request->email)->first();
        }

        if ($customer) {

            return response()->json([

                'status' => true,
                'message' => 'Existing customer found.',

                'customer' => [

                    'id' => $customer->id,
                    'name' => $customer->name,
                    'mobile' => $customer->mobile,
                    'email' => $customer->email,
                    'image' => $customer->image,

                ]

            ]);
        }

        return response()->json([

            'status' => false,
            'message' => 'Customer not found. Create new customer.'

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Create Customer
    |--------------------------------------------------------------------------
    */

    public function createCustomerForAd(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'name' => 'required',

            'mobile' => 'required|unique:customers,mobile',

            'customer_state' => 'required',

            'customer_city' => 'required',

            'email' => 'nullable|unique:customers,email',

        ]);

        if ($validator->fails()) {

            return response()->json([

                'status' => false,

                'errors' => $validator->errors()

            ]);
        }

        $customer = new Customer();

        $customer->name = $request->name;

        $customer->mobile = $request->mobile;

        $customer->email = $request->email;

        $customer->state = $request->customer_state;

        $customer->city = $request->customer_city;

        $customer->country = 1;

        /*
        |--------------------------------------------------------------------------
        | Password
        |--------------------------------------------------------------------------
        */

        $password = $request->password;

        if (empty($password)) {

            $password = rand(11111111, 99999999);
        }

        $customer->password = bcrypt($password);

        /*
        |--------------------------------------------------------------------------
        | Additional Required Fields
        |--------------------------------------------------------------------------
        */

        $customer->status = 0;

        $customer->delete_status = 0;

        $customer->user_type = 'Free';

        $customer->wallet_bonus = 0;

        $customer->no_of_ads = 0;

        $customer->datetime = now();

        $customer->membership_expiry_at = now()->addDays(30);

        $customer->member_id = 'WP' . date('Y') . rand(1000, 9999);

        $customer->is_email_verified = 1;

        /*
        |--------------------------------------------------------------------------
        | Referral Code
        |--------------------------------------------------------------------------
        */

        $namePart = substr($request->name, 0, 4);

        $mobilePart = substr($request->mobile, -4);

        $customer->referral_code = strtoupper($namePart . $mobilePart);

        $customer->save();

        $token = Str::random(64);

        CustomerVerify::create([
            'customer_id' => $customer->id,
            'token' => $token
        ]);

        $mailData = ['token' => $token];

        Mail::to($customer->email)->send(
            new EmailVerificationEmail($mailData)
        );

        $userStateName = States::where('id', $request->customer_state)->first();

        $userCityName = City::where('id', $request->customer_city)->first();

        $customerEmailDetail = array(

            'name' => $customer->name,
            'password' => $password,
            'email' => $customer->email,
            'mobile' => $customer->mobile,
            'member_id' => $customer->member_id,
            'pin' => '',
            'state' => $userStateName->name ?? "",
            'city' => $userCityName->name ?? "",
            'country' => 'India',

        );

        $messagead = '';

        Mail::send('email.new-user-register', $customerEmailDetail, function ($messagead) use ($customerEmailDetail) {

            $messagead->to($customerEmailDetail['email'], $customerEmailDetail['name'])
                ->subject('Welcome to Welcome Post');

            $messagead->from('noreply@yourdomain.com', 'Welcome Post');

        });

        return response()->json([

            'status' => true,

            'message' => 'Customer created successfully.',

            'customer' => [

                'id' => $customer->id,

                'name' => $customer->name,

                'mobile' => $customer->mobile,

                'email' => $customer->email,

                'image' => $customer->image,

            ]

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Open Dynamic Forms
    |--------------------------------------------------------------------------
    */

    public function adminAdForms(Request $request)
    {
        $data['singlecategory'] = Categories::find(
            $request->category_id
        );

        $data['singlesubcatid'] = Subcategories::find(
            $request->subcategory_id
        );

        $data['userinfo'] = Customer::findOrFail(
            $request->user_id
        );

        $data['categoryid'] = $request->category_id;

        $data['subcatid'] = $request->subcategory_id;

        $data['form_id'] = $request->formtype;

        $data['admin_mode'] = true;

        $data['city'] = City::where('delete_status', '0')
            ->orderBy('name', 'asc')
            ->get();

        $data['state'] = States::where('delete_status', '0')
            ->orderBy('name', 'asc')
            ->get();

        $data['jobs'] = Job::where('status', 0)
            ->where('delete_status', 0)
            ->get();

        $data['construction'] = Construction::where('status', 0)
            ->where('delete_status', 0)
            ->get();

        $data['furnishing'] = Furnishing::where('status', 0)
            ->where('delete_status', 0)
            ->get();

        $data['brand'] = Brand::where('type', 'Cars')
            ->where('status', 0)
            ->where('delete_status', 0)
            ->get();

        $data['mobilebrand'] = Brand::where('type', 'Mobile')
            ->where('status', 0)
            ->where('delete_status', 0)
            ->get();

        $data['vehicleType'] = Vehicletypes::where('status', 0)
            ->where('delete_status', 0)
            ->get();

        $data['FuelType'] = Fueltype::where('status', 0)
            ->where('delete_status', 0)
            ->get();

        $data['transmission'] = Transmission::where('status', 0)
            ->where('delete_status', 0)
            ->get();

        $data['residence'] = Residence::where('status', 0)
            ->where('delete_status', 0)
            ->get();

        $data['facing'] = Facing::where('status', 0)
            ->where('delete_status', 0)
            ->get();

        $userIp = $request->ip();

        $data['locationinfo'] = \Location::get($userIp);

        /*
        |--------------------------------------------------------------------------
        | Load Dynamic Forms
        |--------------------------------------------------------------------------
        */

        if ($request->formtype == 1) {

            return view(
                'admin.ads.forms.jobsform',
                $data
            );

        } elseif ($request->formtype == 2) {

            return view(
                'admin.ads.forms.mobileform',
                $data
            );

        } elseif ($request->formtype == 3) {

            return view(
                'admin.ads.forms.vehicleform',
                $data
            );

        } elseif ($request->formtype == 4) {

            return view(
                'admin.ads.forms.propertyform',
                $data
            );

        } else {

            return view(
                'admin.ads.forms.commonform',
                $data
            );
        }
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

        $category_subscription_exists = DB::table("subscription_history")
            ->where('user_id', $user_id)
            ->where('status', '0')
            ->whereDate('subscription_expiry', '>=', date('Y-m-d'))
            ->exists();


        $result_category_id = DB::table('subscriptions_free_trials')
            ->where('category_id', $category_id)
            ->get();

        $ads_validity = $result_category_id[0]->ads_validity ?? 0;

        /*
|--------------------------------------------------------------------------
| AUTO ASSIGN FREE SUBSCRIPTION FOR ADMIN POSTING
|--------------------------------------------------------------------------
*/
        if (!$category_subscription_exists) {

            $freeSubscription = DB::table('subscriptions')
                ->where('is_free', 'yes')
                ->first();


            if ($freeSubscription) {

                $today = date('Y-m-d');

                $subscription_expiry_date = date(
                    'Y-m-d',
                    strtotime($today . ' + ' . $freeSubscription->package_validity . ' days')
                );

                DB::table('subscription_history')->insert([

                    'user_id' => $user_id,
                    'subscription_id' => $freeSubscription->id,

                    'transaction_id' => 'ADMINFREE' . rand(1000, 9999),

                    'payment_method' => 'Admin',
                    'payment_status' => 'Completed',

                    'used_ads' => 0,
                    'remaining_ads' => $freeSubscription->no_of_ads,

                    'subscription_expiry' => $subscription_expiry_date,
                    'subscription_validity' => $freeSubscription->package_validity,

                    'category_id' => $freeSubscription->category_id,

                    'delete_status' => 0,
                    'status' => 0,

                    'subscription_number' => $freeSubscription->subscription_number,

                    'order_number' => 'ORD' . rand(100000, 999999),

                    'type' => 'Free',

                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $category_subscription_exists = true;
            }
        }


        if ($category_subscription_exists) {
            $category_subscription_result = DB::table("subscription_history")
                ->where('user_id', $user_id)
                ->where('status', '0')
                ->whereDate('subscription_expiry', '>=', date('Y-m-d'))
                ->get();

            $usedAdsTotal = 0;
            $remainingAdsTotal = 0;

            foreach ($category_subscription_result as $subscription) {
                $usedAdsTotal += $subscription->used_ads;
                $remainingAdsTotal += $subscription->remaining_ads;
            }

        }

        $no = $ads_validity;
        $dates = date("d-m-Y");
        $date = date_create($dates);
        date_add($date, date_interval_create_from_date_string($no . "days"));
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

        $userprofile->ad_id = mt_rand(1500, 5000);
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
        $userprofile->active_status = '0';
        $userprofile->ad_view_count = '0';
        $userprofile->ads_validity = $ads_validity;
        $userprofile->description = $request->description;
        $userprofile->city = $request->city;
        $userprofile->delete_status = '0';
        $userprofile->status = '1';
        $userprofile->published_date = date("d-m-Y");
        $userprofile->ad_expiry = $category_subscription_result[0]->subscription_expiry;
        $userprofile->active_status = 1;

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

        \Session::put('success', 'Post Added Successfully.');
        return redirect("admin/ads");
    }

    public function post_mobile_forms(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fullname' => 'required|max:50|min:0',
            'email' => 'required|max:50|min:0',
            'mobile' => 'required|max:50|min:0',
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


        $category_subscription_exists = DB::table("subscription_history")
            ->where('user_id', $user_id)
            ->where('status', '0')
            ->whereDate('subscription_expiry', '>=', date('Y-m-d'))
            ->exists();

        $result_category_id = DB::table('subscriptions_free_trials')->where('category_id', $category_id)->get();

        $ads_validity = $result_category_id[0]->ads_validity;

        /*
|--------------------------------------------------------------------------
| AUTO ASSIGN FREE SUBSCRIPTION FOR ADMIN POSTING
|--------------------------------------------------------------------------
*/

        if (!$category_subscription_exists) {

            $freeSubscription = DB::table('subscriptions')
                ->where('is_free', 'yes')
                ->first();

            if ($freeSubscription) {

                $today = date('Y-m-d');

                $subscription_expiry_date = date(
                    'Y-m-d',
                    strtotime($today . ' + ' . $freeSubscription->package_validity . ' days')
                );

                DB::table('subscription_history')->insert([

                    'user_id' => $user_id,
                    'subscription_id' => $freeSubscription->id,

                    'transaction_id' => 'ADMINFREE' . rand(1000, 9999),

                    'payment_method' => 'Admin',
                    'payment_status' => 'Completed',

                    'used_ads' => 0,
                    'remaining_ads' => $freeSubscription->no_of_ads,

                    'subscription_expiry' => $subscription_expiry_date,
                    'subscription_validity' => $freeSubscription->package_validity,

                    'category_id' => $freeSubscription->category_id,

                    'delete_status' => 0,
                    'status' => 0,

                    'subscription_number' => $freeSubscription->subscription_number,

                    'order_number' => 'ORD' . rand(100000, 999999),

                    'type' => 'Free',

                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $category_subscription_exists = true;
            }
        }

        if ($category_subscription_exists) {

            $category_subscription_result = DB::table("subscription_history")
                ->where('user_id', $user_id)
                ->where('status', '0')
                ->orderBy('created_at', 'DESC')
                ->get();

            $usedAdsTotal = 0;
            $remainingAdsTotal = 0;

            foreach ($category_subscription_result as $subscription) {
                $usedAdsTotal += $subscription->used_ads;
                $remainingAdsTotal += $subscription->remaining_ads;
            }


        }

        $no = $ads_validity;
        $dates = date("d-m-Y");
        $date = date_create($dates);

        date_add($date, date_interval_create_from_date_string($no . "days"));

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

        $userprofile->ad_id = mt_rand(1500, 5000);
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
        $userprofile->active_status = '0';
        $userprofile->ad_view_count = '0';
        $userprofile->ads_validity = $ads_validity;
        $userprofile->description = $request->description;
        $userprofile->delete_status = '0';
        $userprofile->status = '1';
        $userprofile->published_date = date("d-m-Y");
        $userprofile->ad_expiry = $category_subscription_result[0]->subscription_expiry;
        $userprofile->active_status = 1;
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

        \Session::put('success', 'Post Added Successfully.');
        return redirect("admin/ads");
    }

    public function post_vehicle_forms(Request $request)
    {
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

        $category_subscription_exists = DB::table("subscription_history")
            ->where('user_id', $user_id)
            ->where('status', '0')
            ->whereDate('subscription_expiry', '>=', date('Y-m-d'))
            ->exists();

        $result_category_id = DB::table('subscriptions_free_trials')->where('category_id', $category_id)->get();
        $ads_validity = $result_category_id[0]->ads_validity;
        /*
|--------------------------------------------------------------------------
| AUTO ASSIGN FREE SUBSCRIPTION FOR ADMIN POSTING
|--------------------------------------------------------------------------
*/

        if (!$category_subscription_exists) {

            $freeSubscription = DB::table('subscriptions')
                ->where('is_free', 'yes')
                ->first();

            if ($freeSubscription) {

                $today = date('Y-m-d');

                $subscription_expiry_date = date(
                    'Y-m-d',
                    strtotime($today . ' + ' . $freeSubscription->package_validity . ' days')
                );

                DB::table('subscription_history')->insert([

                    'user_id' => $user_id,
                    'subscription_id' => $freeSubscription->id,

                    'transaction_id' => 'ADMINFREE' . rand(1000, 9999),

                    'payment_method' => 'Admin',
                    'payment_status' => 'Completed',

                    'used_ads' => 0,
                    'remaining_ads' => $freeSubscription->no_of_ads,

                    'subscription_expiry' => $subscription_expiry_date,
                    'subscription_validity' => $freeSubscription->package_validity,

                    'category_id' => $freeSubscription->category_id,

                    'delete_status' => 0,
                    'status' => 0,

                    'subscription_number' => $freeSubscription->subscription_number,

                    'order_number' => 'ORD' . rand(100000, 999999),

                    'type' => 'Free',

                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $category_subscription_exists = true;
            }
        }

        if ($category_subscription_exists) {


            $category_subscription_result = DB::table("subscription_history")
                ->where('user_id', $user_id)
                ->where('status', '0')
                ->orderBy('created_at', 'DESC')
                ->get();

            $usedAdsTotal = 0;
            $remainingAdsTotal = 0;

            foreach ($category_subscription_result as $subscription) {
                $usedAdsTotal += $subscription->used_ads;
                $remainingAdsTotal += $subscription->remaining_ads;
            }

        }

        $no = $ads_validity;
        $dates = date("d-m-Y");
        $date = date_create($dates);

        date_add($date, date_interval_create_from_date_string($no . "days"));

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
        $userprofile->active_status = '0';
        $userprofile->ad_view_count = '0';
        $userprofile->ads_validity = $ads_validity;
        $userprofile->description = $request->description;
        $userprofile->subscription_id = $category_subscription_result[0]->id;
        $userprofile->delete_status = '0';
        $userprofile->status = '1';
        $userprofile->published_date = date("d-m-Y");
        $userprofile->ad_expiry = $category_subscription_result[0]->subscription_expiry;
        $userprofile->active_status = 1;

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
        return redirect("admin/ads");
    }

    public function post_property_forms(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'property_type' => 'required',
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


        $category_subscription_exists = DB::table("subscription_history")
            ->where('status', '0')
            ->where('user_id', $user_id)
            ->whereDate('subscription_expiry', '>=', date('Y-m-d'))
            ->exists();


        $result_category_id = DB::table('subscriptions_free_trials')->where('category_id', $category_id)->get();
        $ads_validity = $result_category_id[0]->ads_validity;

        /*
|--------------------------------------------------------------------------
| AUTO ASSIGN FREE SUBSCRIPTION FOR ADMIN POSTING
|--------------------------------------------------------------------------
*/

        if (!$category_subscription_exists) {

            $freeSubscription = DB::table('subscriptions')
                ->where('is_free', 'yes')
                ->first();

            if ($freeSubscription) {

                $today = date('Y-m-d');

                $subscription_expiry_date = date(
                    'Y-m-d',
                    strtotime($today . ' + ' . $freeSubscription->package_validity . ' days')
                );

                DB::table('subscription_history')->insert([

                    'user_id' => $user_id,
                    'subscription_id' => $freeSubscription->id,

                    'transaction_id' => 'ADMINFREE' . rand(1000, 9999),

                    'payment_method' => 'Admin',
                    'payment_status' => 'Completed',

                    'used_ads' => 0,
                    'remaining_ads' => $freeSubscription->no_of_ads,

                    'subscription_expiry' => $subscription_expiry_date,
                    'subscription_validity' => $freeSubscription->package_validity,

                    'category_id' => $freeSubscription->category_id,

                    'delete_status' => 0,
                    'status' => 0,

                    'subscription_number' => $freeSubscription->subscription_number,

                    'order_number' => 'ORD' . rand(100000, 999999),

                    'type' => 'Free',

                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $category_subscription_exists = true;
            }
        }


        if ($category_subscription_exists) {

            $category_subscription_result = DB::table("subscription_history")
                ->where('user_id', $user_id)
                ->where('status', '0')
                ->whereDate('subscription_expiry', '>=', date('Y-m-d'))
                ->orderBy('created_at', 'DESC')
                ->get();

            $usedAdsTotal = 0;
            $remainingAdsTotal = 0;

            foreach ($category_subscription_result as $subscription) {
                $usedAdsTotal += $subscription->used_ads;
                $remainingAdsTotal += $subscription->remaining_ads;
            }

        }

        $no = $ads_validity;
        $dates = date("d-m-Y");
        $date = date_create($dates);

        date_add($date, date_interval_create_from_date_string($no . "days"));

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
        $userprofile->active_status = '0';
        $userprofile->ad_view_count = '0';
        $userprofile->ads_validity = $ads_validity;
        $userprofile->description = $request->description;
        $userprofile->delete_status = '0';
        $userprofile->status = '1';
        $userprofile->published_date = date("d-m-Y");
        $userprofile->ad_expiry = $category_subscription_result[0]->subscription_expiry;
        $userprofile->active_status = 1;
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

        \Session::put('success', 'Post Added Successfully.');
        return redirect("admin/ads");
    }

    public function post_common_forms(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fullname' => 'required|max:50',
            'email' => 'required|email|max:50',
            'mobile' => 'required|max:50',
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

        $category_subscription_exists = DB::table("subscription_history")
            ->where('user_id', $user_id)
            ->where('status', '0')
            ->whereDate('subscription_expiry', '>=', date('Y-m-d'))
            ->exists();

        $category_id_exists = DB::table('subscriptions_free_trials')->where('category_id', $category_id)->exists();
        $result_category_id = DB::table('subscriptions_free_trials')->where('category_id', $category_id)->get();

        $ads_validity = $result_category_id[0]->ads_validity;
        $no_of_ads = $result_category_id[0]->no_of_ads;
        $active_postings_count = DB::table('ads_postings')->where('user_id', $user_id)->where('ad_type', 'Free')->where('active_status', '1')->count('id');
        $ads_postings_count = DB::table('ads_postings')
            ->where('user_id', $user_id)
            ->where('delete_status', 0)
            ->count('id');

        /*
|--------------------------------------------------------------------------
| AUTO ASSIGN FREE SUBSCRIPTION FOR ADMIN POSTING
|--------------------------------------------------------------------------
*/

        if (!$category_subscription_exists) {

            $freeSubscription = DB::table('subscriptions')
                ->where('is_free', 'yes')
                ->first();

            if ($freeSubscription) {

                $today = date('Y-m-d');

                $subscription_expiry_date = date(
                    'Y-m-d',
                    strtotime($today . ' + ' . $freeSubscription->package_validity . ' days')
                );

                DB::table('subscription_history')->insert([

                    'user_id' => $user_id,
                    'subscription_id' => $freeSubscription->id,

                    'transaction_id' => 'ADMINFREE' . rand(1000, 9999),

                    'payment_method' => 'Admin',
                    'payment_status' => 'Completed',

                    'used_ads' => 0,
                    'remaining_ads' => $freeSubscription->no_of_ads,

                    'subscription_expiry' => $subscription_expiry_date,
                    'subscription_validity' => $freeSubscription->package_validity,

                    'category_id' => $freeSubscription->category_id,

                    'delete_status' => 0,
                    'status' => 0,

                    'subscription_number' => $freeSubscription->subscription_number,

                    'order_number' => 'ORD' . rand(100000, 999999),

                    'type' => 'Free',

                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $category_subscription_exists = true;
            }
        }



        if ($category_subscription_exists) {
            $category_subscription_result = DB::table("subscription_history")
                ->where('user_id', $user_id)
                ->where('status', '0')
                ->whereDate('subscription_expiry', '>=', date('Y-m-d'))
                ->orderBy('created_at', 'DESC')
                ->get();

            $payment_status = $category_subscription_result[0]->payment_status;
            $usedAdsTotal = 0;
            $remainingAdsTotal = 0;



            foreach ($category_subscription_result as $subscription) {
                $usedAdsTotal += $subscription->used_ads;
                $remainingAdsTotal += $subscription->remaining_ads;
            }

            $status = $category_subscription_result[0]->status;

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
        $userprofile->active_status = '0';
        $userprofile->ad_view_count = '0';
        $userprofile->ads_validity = $ads_validity;
        $userprofile->description = $request->description;
        $userprofile->delete_status = '0';
        $userprofile->status = '1';
        $userprofile->published_date = date("d-m-Y");
        $userprofile->ad_expiry = $category_subscription_result[0]->subscription_expiry;
        $userprofile->active_status = 1;

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
        return redirect("admin/ads");
    }


    public function editAd($id)
    {
        $ad = AdPosting::findOrFail($id);

        $data['ad'] = $ad;

        $data['userinfo'] = Customer::find($ad->user_id);

        $data['singlecategory'] = Categories::find($ad->category_id);

        $data['singlesubcatid'] = Subcategories::find($ad->sub_category_id);

        $data['categoryid'] = $ad->category_id;

        $data['subcatid'] = $ad->sub_category_id;

        $data['form_id'] = $ad->formtype;

        $data['city'] = City::where('delete_status', '0')
            ->orderBy('name', 'asc')
            ->get();

        $data['state'] = States::where('delete_status', '0')
            ->orderBy('name', 'asc')
            ->get();

        $data['jobs'] = Job::where('status', 0)
            ->where('delete_status', 0)
            ->get();

        $data['construction'] = Construction::where('status', 0)
            ->where('delete_status', 0)
            ->get();

        $data['furnishing'] = Furnishing::where('status', 0)
            ->where('delete_status', 0)
            ->get();

        $data['brand'] = Brand::where('type', 'Cars')
            ->where('status', 0)
            ->where('delete_status', 0)
            ->get();

        $data['mobilebrand'] = Brand::where('type', 'Mobile')
            ->where('status', 0)
            ->where('delete_status', 0)
            ->get();

        $data['vehicleType'] = Vehicletypes::where('status', 0)
            ->where('delete_status', 0)
            ->get();

        $data['FuelType'] = Fueltype::where('status', 0)
            ->where('delete_status', 0)
            ->get();

        $data['transmission'] = Transmission::where('status', 0)
            ->where('delete_status', 0)
            ->get();

        $data['residence'] = Residence::where('status', 0)
            ->where('delete_status', 0)
            ->get();

        $data['facing'] = Facing::where('status', 0)
            ->where('delete_status', 0)
            ->get();

        $data['images'] = AdPostingImage::where(
            'ads_id',
            $ad->ad_id
        )->get();

        /*
        |--------------------------------------------------------------------------
        | FORM DATA
        |--------------------------------------------------------------------------
        */

        if ($ad->formtype == 1) {

            $data['formData'] = Jobforms::where('ads_id', $ad->ad_id)->first();

            return view('admin.ads.edit.job-form', $data);

        } elseif ($ad->formtype == 2) {

            $data['formData'] = Mobileforms::where('ads_id', $ad->ad_id)->first();

            return view('admin.ads.edit.mobile-form', $data);

        } elseif ($ad->formtype == 3) {

            $data['formData'] = Vehicleform::where('ads_id', $ad->ad_id)->first();

            return view('admin.ads.edit.vehicle-form', $data);

        } elseif ($ad->formtype == 4) {

            $data['formData'] = Propertyform::where('ads_id', $ad->ad_id)->first();
            return view('admin.ads.edit.property-form', $data);

        } else {

            $data['formData'] = Commonform::where('ads_id', $ad->ad_id)->first();

            return view('admin.ads.edit.common-form', $data);
        }
    }

    public function updateJobForm(Request $request, $id)
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

            'file' => 'nullable|image|max:8192',
            'file1' => 'nullable|image|max:8192',
            'file2' => 'nullable|image|max:8192',
            'file3' => 'nullable|image|max:8192',
            'file4' => 'nullable|image|max:8192',

        ]);

        if ($validator->fails()) {

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | MAIN AD
        |--------------------------------------------------------------------------
        */

        $ad = Adposting::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | JOB FORM
        |--------------------------------------------------------------------------
        */

        $jobForm = Jobforms::where(
            'ads_id',
            $ad->ad_id
        )->first();

        /*
        |--------------------------------------------------------------------------
        | UPDATE MAIN TABLE
        |--------------------------------------------------------------------------
        */

        $ad->fullname = $request->fullname;

        $ad->email = $request->email;

        $ad->mobile = $request->mobile;

        $ad->salary_from = $request->salary_from;

        $ad->salary_to = $request->salary_to;

        $ad->ad_title = $request->ad_title;

        $ad->description = $request->description;

        $ad->city = $request->city;

        /*
        |--------------------------------------------------------------------------
        | UPDATE MAIN IMAGE
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('file')) {

            $imageName = time() . '.' . $request->file->extension();

            $request->file->move(
                public_path('uploads/ads'),
                $imageName
            );

            $imagePath = public_path(
                'uploads/ads/' . $imageName
            );

            ImageOptimizer::optimize($imagePath);

            $imageUrl = url('public/uploads/ads')
                . '/' . $imageName;

            $ad->image = $imageUrl;

            /*
            |--------------------------------------------------------------------------
            | UPDATE IMAGE TABLE
            |--------------------------------------------------------------------------
            */

            $mainImage = AdPostingImage::where(
                'ads_id',
                $ad->ad_id
            )->where(
                    'image_no',
                    '1'
                )->first();

            if ($mainImage) {

                $mainImage->image = $imageUrl;

                $mainImage->save();

            } else {

                AdPostingImage::create([

                    'ads_id' => $ad->ad_id,

                    'image' => $imageUrl,

                    'image_no' => '1'

                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | EXTRA IMAGES
        |--------------------------------------------------------------------------
        */

        for ($i = 1; $i <= 4; $i++) {

            $field = 'file' . $i;

            $imageNo = $i + 1;

            if ($request->hasFile($field)) {

                $file = $request->file($field);

                $name = time() . rand(1, 100)
                    . '.' . $file->extension();

                $file->move(
                    public_path('uploads/ads'),
                    $name
                );

                $imagePath = public_path(
                    'uploads/ads/' . $name
                );

                ImageOptimizer::optimize($imagePath);

                $imageUrl = url('public/uploads/ads')
                    . '/' . $name;

                $imageData = AdPostingImage::where(
                    'ads_id',
                    $ad->ad_id
                )->where(
                        'image_no',
                        $imageNo
                    )->first();

                if ($imageData) {

                    $imageData->image = $imageUrl;

                    $imageData->save();

                } else {

                    AdPostingImage::create([

                        'ads_id' => $ad->ad_id,

                        'image' => $imageUrl,

                        'image_no' => $imageNo

                    ]);
                }
            }
        }

        $ad->save();

        /*
        |--------------------------------------------------------------------------
        | UPDATE JOB FORM TABLE
        |--------------------------------------------------------------------------
        */

        if ($jobForm) {

            $jobForm->image = $ad->image;

            $jobForm->fullname = $request->fullname;

            $jobForm->email = $request->email;

            $jobForm->mobile = $request->mobile;

            $jobForm->state = $request->state;

            $jobForm->state_name = $request->state_name;

            $jobForm->city = $request->city;

            $jobForm->city_name = $request->city_name;

            $jobForm->neibourhood = $request->neibourhood;

            $jobForm->salary_period = $request->salary_period;

            $jobForm->position_type = $request->position_type;

            $jobForm->salary_from = $request->salary_from;

            $jobForm->salary_to = $request->salary_to;

            $jobForm->ad_title = $request->ad_title;

            $jobForm->description = $request->description;

            $jobForm->save();
        }

        return redirect('admin/ads')
            ->with('success', 'Job Advertisement Updated Successfully.');
    }

    public function updateMobileForm(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [

            'fullname' => 'required|max:50|min:0',
            'email' => 'required|max:50|min:0',
            'mobile' => 'required|max:50|min:0',

            'brands' => 'required|max:50|min:0',

            'price' => 'required|max:50|min:0',

            'ad_title' => 'required|max:50|min:0',

            'description' => 'required|max:2500|min:0',

            'file' => 'nullable|image|max:8192',
            'file1' => 'nullable|image|max:8192',
            'file2' => 'nullable|image|max:8192',
            'file3' => 'nullable|image|max:8192',
            'file4' => 'nullable|image|max:8192',

        ]);

        if ($validator->fails()) {

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | MAIN AD
        |--------------------------------------------------------------------------
        */

        $ad = Adposting::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | MOBILE FORM
        |--------------------------------------------------------------------------
        */

        $mobileForm = Mobileform::where(
            'ads_id',
            $ad->ad_id
        )->first();

        /*
        |--------------------------------------------------------------------------
        | MOBILE HIDE
        |--------------------------------------------------------------------------
        */

        if (isset($request->is_mobile_hide)) {

            $hide_mobile = '1';

        } else {

            $hide_mobile = '0';
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE MAIN TABLE
        |--------------------------------------------------------------------------
        */

        $ad->fullname = $request->fullname;

        $ad->email = $request->email;

        $ad->mobile = $request->mobile;

        $ad->is_mobile_hide = $hide_mobile;

        $ad->city = $request->city;

        $ad->price = $request->price;

        $ad->ad_title = $request->ad_title;

        $ad->description = $request->description;

        /*
        |--------------------------------------------------------------------------
        | MAIN IMAGE
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('file')) {

            $imageName = time() . '.' . $request->file->extension();

            $request->file->move(
                public_path('uploads/ads'),
                $imageName
            );

            $imagePath = public_path(
                'uploads/ads/' . $imageName
            );

            ImageOptimizer::optimize($imagePath);

            $imageUrl = url('public/uploads/ads')
                . '/' . $imageName;

            $ad->image = $imageUrl;

            /*
            |--------------------------------------------------------------------------
            | UPDATE IMAGE TABLE
            |--------------------------------------------------------------------------
            */

            $mainImage = AdPostingImage::where(
                'ads_id',
                $ad->ad_id
            )->where(
                    'image_no',
                    '1'
                )->first();

            if ($mainImage) {

                $mainImage->image = $imageUrl;

                $mainImage->save();

            } else {

                AdPostingImage::create([

                    'ads_id' => $ad->ad_id,

                    'image' => $imageUrl,

                    'image_no' => '1'

                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | EXTRA IMAGES
        |--------------------------------------------------------------------------
        */

        for ($i = 1; $i <= 4; $i++) {

            $field = 'file' . $i;

            $imageNo = $i + 1;

            if ($request->hasFile($field)) {

                $file = $request->file($field);

                $name = time() . rand(1, 100)
                    . '.' . $file->extension();

                $file->move(
                    public_path('uploads/ads'),
                    $name
                );

                $imagePath = public_path(
                    'uploads/ads/' . $name
                );

                ImageOptimizer::optimize($imagePath);

                $imageUrl = url('public/uploads/ads')
                    . '/' . $name;

                $imageData = AdPostingImage::where(
                    'ads_id',
                    $ad->ad_id
                )->where(
                        'image_no',
                        $imageNo
                    )->first();

                if ($imageData) {

                    $imageData->image = $imageUrl;

                    $imageData->save();

                } else {

                    AdPostingImage::create([

                        'ads_id' => $ad->ad_id,

                        'image' => $imageUrl,

                        'image_no' => $imageNo

                    ]);
                }
            }
        }

        $ad->save();

        /*
        |--------------------------------------------------------------------------
        | UPDATE MOBILE FORM TABLE
        |--------------------------------------------------------------------------
        */

        if ($mobileForm) {

            $mobileForm->image = $ad->image;

            $mobileForm->fullname = $request->fullname;

            $mobileForm->email = $request->email;

            $mobileForm->mobile = $request->mobile;

            $mobileForm->state = $request->state;

            $mobileForm->state_name = $request->state_name;

            $mobileForm->city = $request->city;

            $mobileForm->city_name = $request->city_name;

            $mobileForm->neibourhood = $request->neibourhood;

            $mobileForm->brand = $request->brands;

            $mobileForm->price = $request->price;

            $mobileForm->ad_title = $request->ad_title;

            $mobileForm->description = $request->description;

            $mobileForm->save();
        }

        return redirect('ads')
            ->with('success', 'Mobile Advertisement Updated Successfully.');
    }

    public function updateVehicleForm(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [

            'brands' => 'required',
            'vehicle_type' => 'required',
            'fuel_type' => 'required',
            'transmission' => 'required',
            'year' => 'required',

            'fullname' => 'required|max:50|min:0',
            'email' => 'required|max:50|min:0',

            'file' => 'nullable|image|max:8192',
            'file1' => 'nullable|image|max:8192',
            'file2' => 'nullable|image|max:8192',
            'file3' => 'nullable|image|max:8192',
            'file4' => 'nullable|image|max:8192',

            'price' => 'required|max:50|min:0',

            'ad_title' => 'required|max:50|min:0',

            'description' => 'required|max:2500|min:0',

        ]);

        if ($validator->fails()) {

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | MAIN AD
        |--------------------------------------------------------------------------
        */

        $ad = Adposting::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | VEHICLE FORM
        |--------------------------------------------------------------------------
        */

        $vehicleForm = Vehicleform::where(
            'ads_id',
            $ad->ad_id
        )->first();

        /*
        |--------------------------------------------------------------------------
        | MOBILE HIDE
        |--------------------------------------------------------------------------
        */

        if (isset($request->is_mobile_hide)) {

            $hide_mobile = '1';

        } else {

            $hide_mobile = '0';
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE MAIN TABLE
        |--------------------------------------------------------------------------
        */

        $ad->fullname = $request->fullname;

        $ad->email = $request->email;

        $ad->mobile = $request->mobile;

        $ad->is_mobile_hide = $hide_mobile;

        $ad->city = $request->city;

        $ad->price = $request->price;

        $ad->ad_title = $request->ad_title;

        $ad->description = $request->description;

        /*
        |--------------------------------------------------------------------------
        | MAIN IMAGE
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('file')) {

            $imageName = time() . '.' . $request->file->extension();

            $request->file->move(
                public_path('uploads/ads'),
                $imageName
            );

            $imagePath = public_path(
                'uploads/ads/' . $imageName
            );

            ImageOptimizer::optimize($imagePath);

            $imageUrl = url('public/uploads/ads')
                . '/' . $imageName;

            $ad->image = $imageUrl;

            /*
            |--------------------------------------------------------------------------
            | UPDATE IMAGE TABLE
            |--------------------------------------------------------------------------
            */

            $mainImage = AdPostingImage::where(
                'ads_id',
                $ad->ad_id
            )->where(
                    'image_no',
                    '1'
                )->first();

            if ($mainImage) {

                $mainImage->image = $imageUrl;

                $mainImage->save();

            } else {

                AdPostingImage::create([

                    'ads_id' => $ad->ad_id,

                    'image' => $imageUrl,

                    'image_no' => '1'

                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | EXTRA IMAGES
        |--------------------------------------------------------------------------
        */

        for ($i = 1; $i <= 4; $i++) {

            $field = 'file' . $i;

            $imageNo = $i + 1;

            if ($request->hasFile($field)) {

                $file = $request->file($field);

                $name = time() . rand(1, 100)
                    . '.' . $file->extension();

                $file->move(
                    public_path('uploads/ads'),
                    $name
                );

                $imagePath = public_path(
                    'uploads/ads/' . $name
                );

                ImageOptimizer::optimize($imagePath);

                $imageUrl = url('public/uploads/ads')
                    . '/' . $name;

                $imageData = AdPostingImage::where(
                    'ads_id',
                    $ad->ad_id
                )->where(
                        'image_no',
                        $imageNo
                    )->first();

                if ($imageData) {

                    $imageData->image = $imageUrl;

                    $imageData->save();

                } else {

                    AdPostingImage::create([

                        'ads_id' => $ad->ad_id,

                        'image' => $imageUrl,

                        'image_no' => $imageNo

                    ]);
                }
            }
        }

        $ad->save();

        /*
        |--------------------------------------------------------------------------
        | UPDATE VEHICLE FORM TABLE
        |--------------------------------------------------------------------------
        */

        if ($vehicleForm) {

            $vehicleForm->image = $ad->image;

            $vehicleForm->fullname = $request->fullname;

            $vehicleForm->email = $request->email;

            $vehicleForm->mobile = $request->mobile;

            $vehicleForm->state = $request->state;

            $vehicleForm->state_name = $request->state_name;

            $vehicleForm->city = $request->city;

            $vehicleForm->city_name = $request->city_name;

            $vehicleForm->neibourhood = $request->neibourhood;

            $vehicleForm->price = $request->price;

            $vehicleForm->ad_title = $request->ad_title;

            $vehicleForm->description = $request->description;

            $vehicleForm->brand = $request->brands;

            $vehicleForm->vehicle_type = $request->vehicle_type;

            $vehicleForm->fuel_type = $request->fuel_type;

            $vehicleForm->transmission = $request->transmission;

            $vehicleForm->year = $request->year;

            $vehicleForm->km = $request->km;

            $vehicleForm->save();
        }

        return redirect('admin/ads')
            ->with('success', 'Vehicle Advertisement Updated Successfully.');
    }

    public function updatePropertyForm(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [

            'property_type' => 'required',
            'residence_status' => 'required',
            'furnishing_status' => 'required',
            'construction_status' => 'required',
            'listed_by' => 'required',
            'plot_type' => 'required',
            'price_mention' => 'required',
            'car_parking' => 'required',
            'facing' => 'required',
            'project_name' => 'required',

            'file' => 'nullable|image|max:8192',
            'file1' => 'nullable|image|max:8192',
            'file2' => 'nullable|image|max:8192',
            'file3' => 'nullable|image|max:8192',
            'file4' => 'nullable|image|max:8192',

            'fullname' => 'required|max:50|min:0',
            'email' => 'required|max:50|min:0',
            'mobile' => 'required|max:50|min:0',

            'price' => 'required|max:50|min:0',

            'ad_title' => 'required|max:50|min:0',

            'description' => 'required|max:2500|min:0',

        ]);

        if ($validator->fails()) {

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | MAIN AD
        |--------------------------------------------------------------------------
        */

        $ad = Adposting::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | PROPERTY FORM
        |--------------------------------------------------------------------------
        */

        $propertyForm = Propertyform::where(
            'ads_id',
            $ad->ad_id
        )->first();

        /*
        |--------------------------------------------------------------------------
        | MOBILE HIDE
        |--------------------------------------------------------------------------
        */

        if (isset($request->is_mobile_hide)) {

            $hide_mobile = '1';

        } else {

            $hide_mobile = '0';
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE MAIN TABLE
        |--------------------------------------------------------------------------
        */

        $ad->fullname = $request->fullname;

        $ad->email = $request->email;

        $ad->mobile = $request->mobile;

        $ad->is_mobile_hide = $hide_mobile;

        $ad->city = $request->city;

        $ad->price = $request->price;

        $ad->ad_title = $request->ad_title;

        $ad->description = $request->description;

        /*
        |--------------------------------------------------------------------------
        | MAIN IMAGE
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('file')) {

            $imageName = time() . '.' . $request->file->extension();

            $request->file->move(
                public_path('uploads/ads'),
                $imageName
            );

            $imagePath = public_path(
                'uploads/ads/' . $imageName
            );

            ImageOptimizer::optimize($imagePath);

            $imageUrl = url('public/uploads/ads')
                . '/' . $imageName;

            $ad->image = $imageUrl;

            /*
            |--------------------------------------------------------------------------
            | UPDATE IMAGE TABLE
            |--------------------------------------------------------------------------
            */

            $mainImage = AdPostingImage::where(
                'ads_id',
                $ad->ad_id
            )->where(
                    'image_no',
                    '1'
                )->first();

            if ($mainImage) {

                $mainImage->image = $imageUrl;

                $mainImage->save();

            } else {

                AdPostingImage::create([

                    'ads_id' => $ad->ad_id,

                    'image' => $imageUrl,

                    'image_no' => '1'

                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | EXTRA IMAGES
        |--------------------------------------------------------------------------
        */

        for ($i = 1; $i <= 4; $i++) {

            $field = 'file' . $i;

            $imageNo = $i + 1;

            if ($request->hasFile($field)) {

                $file = $request->file($field);

                $name = time() . rand(1, 100)
                    . '.' . $file->extension();

                $file->move(
                    public_path('uploads/ads'),
                    $name
                );

                $imagePath = public_path(
                    'uploads/ads/' . $name
                );

                ImageOptimizer::optimize($imagePath);

                $imageUrl = url('public/uploads/ads')
                    . '/' . $name;

                $imageData = AdPostingImage::where(
                    'ads_id',
                    $ad->ad_id
                )->where(
                        'image_no',
                        $imageNo
                    )->first();

                if ($imageData) {

                    $imageData->image = $imageUrl;

                    $imageData->save();

                } else {

                    AdPostingImage::create([

                        'ads_id' => $ad->ad_id,

                        'image' => $imageUrl,

                        'image_no' => $imageNo

                    ]);
                }
            }
        }

        $ad->save();

        /*
        |--------------------------------------------------------------------------
        | UPDATE PROPERTY FORM TABLE
        |--------------------------------------------------------------------------
        */

        if ($propertyForm) {

            $propertyForm->image = $ad->image;

            $propertyForm->ads_id = $ad->ad_id;

            $propertyForm->fullname = $request->fullname;

            $propertyForm->email = $request->email;

            $propertyForm->mobile = $request->mobile;

            $propertyForm->state = $request->state;

            $propertyForm->state_name = $request->state_name;

            $propertyForm->city = $request->city;

            $propertyForm->city_name = $request->city_name;

            $propertyForm->neibourhood = $request->neibourhood;

            $propertyForm->price = $request->price;

            $propertyForm->ad_title = $request->ad_title;

            $propertyForm->description = $request->description;

            $propertyForm->property_type = $request->property_type;

            $propertyForm->bedroom = $request->bedroom;

            $propertyForm->bathroom = $request->bathroom;

            $propertyForm->furnishing_status = $request->furnishing_status;

            $propertyForm->construction_status = $request->construction_status;

            $propertyForm->residence = $request->residence_status;

            $propertyForm->listed_by = $request->listed_by;

            $propertyForm->plot_type = $request->plot_type;

            $propertyForm->price_mention = $request->price_mention;

            $propertyForm->builtup_area = $request->builtup_area;

            $propertyForm->carpet_area = $request->carpet_area;

            $propertyForm->maintenance = $request->maintenance;

            $propertyForm->total_floor = $request->total_floor;

            $propertyForm->floor_no = $request->floor_no;

            $propertyForm->car_parking = $request->car_parking;

            $propertyForm->facing = $request->facing;

            $propertyForm->project_name = $request->project_name;

            $propertyForm->save();
        }

        return redirect('admin/ads')
            ->with('success', 'Property Advertisement Updated Successfully.');
    }

    public function updateCommonForm(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [

            'fullname' => 'required|max:50',
            'email' => 'required|email|max:50',
            'mobile' => 'required|max:50',

            'price' => 'required|numeric|min:0',

            'ad_title' => 'required|max:50',

            'description' => 'required|max:2500',

            'file' => 'nullable|image|max:8192',
            'file1' => 'nullable|image|max:8192',
            'file2' => 'nullable|image|max:8192',
            'file3' => 'nullable|image|max:8192',
            'file4' => 'nullable|image|max:8192',

        ]);

        if ($validator->fails()) {

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | MAIN AD
        |--------------------------------------------------------------------------
        */

        $ad = Adposting::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | COMMON FORM
        |--------------------------------------------------------------------------
        */

        $commonForm = Commonform::where(
            'ads_id',
            $ad->ad_id
        )->first();

        /*
        |--------------------------------------------------------------------------
        | MOBILE HIDE
        |--------------------------------------------------------------------------
        */

        if (isset($request->is_mobile_hide)) {

            $hide_mobile = '1';

        } else {

            $hide_mobile = '0';
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE MAIN TABLE
        |--------------------------------------------------------------------------
        */

        $ad->fullname = $request->fullname;

        $ad->email = $request->email;

        $ad->mobile = $request->mobile;

        $ad->is_mobile_hide = $hide_mobile;

        $ad->city = $request->city;

        $ad->price = $request->price;

        $ad->ad_title = $request->ad_title;

        $ad->description = $request->description;

        /*
        |--------------------------------------------------------------------------
        | MAIN IMAGE
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('file')) {

            $imageName = time() . '.' . $request->file->extension();

            $request->file->move(
                public_path('uploads/ads'),
                $imageName
            );

            $imagePath = public_path(
                'uploads/ads/' . $imageName
            );

            ImageOptimizer::optimize($imagePath);

            $imageUrl = url('public/uploads/ads')
                . '/' . $imageName;

            $ad->image = $imageUrl;

            /*
            |--------------------------------------------------------------------------
            | UPDATE IMAGE TABLE
            |--------------------------------------------------------------------------
            */

            $mainImage = AdPostingImage::where(
                'ads_id',
                $ad->ad_id
            )->where(
                    'image_no',
                    '1'
                )->first();

            if ($mainImage) {

                $mainImage->image = $imageUrl;

                $mainImage->save();

            } else {

                AdPostingImage::create([

                    'ads_id' => $ad->ad_id,

                    'image' => $imageUrl,

                    'image_no' => '1'

                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | EXTRA IMAGES
        |--------------------------------------------------------------------------
        */

        for ($i = 1; $i <= 4; $i++) {

            $field = 'file' . $i;

            $imageNo = $i + 1;

            if ($request->hasFile($field)) {

                $file = $request->file($field);

                $name = time() . rand(1, 100)
                    . '.' . $file->extension();

                $file->move(
                    public_path('uploads/ads'),
                    $name
                );

                $imagePath = public_path(
                    'uploads/ads/' . $name
                );

                ImageOptimizer::optimize($imagePath);

                $imageUrl = url('public/uploads/ads')
                    . '/' . $name;

                $imageData = AdPostingImage::where(
                    'ads_id',
                    $ad->ad_id
                )->where(
                        'image_no',
                        $imageNo
                    )->first();

                if ($imageData) {

                    $imageData->image = $imageUrl;

                    $imageData->save();

                } else {

                    AdPostingImage::create([

                        'ads_id' => $ad->ad_id,

                        'image' => $imageUrl,

                        'image_no' => $imageNo

                    ]);
                }
            }
        }

        $ad->save();

        /*
        |--------------------------------------------------------------------------
        | UPDATE COMMON FORM TABLE
        |--------------------------------------------------------------------------
        */

        if ($commonForm) {

            $commonForm->image = $ad->image;

            $commonForm->fullname = $request->fullname;

            $commonForm->email = $request->email;

            $commonForm->mobile = $request->mobile;

            $commonForm->state = $request->state;

            $commonForm->state_name = $request->state_name;

            $commonForm->city = $request->city;

            $commonForm->city_name = $request->city_name;

            $commonForm->neibourhood = $request->neibourhood;

            $commonForm->price = $request->price;

            $commonForm->ad_title = $request->ad_title;

            $commonForm->description = $request->description;

            $commonForm->save();
        }

        return redirect('admin/ads')
            ->with('success', 'Advertisement Updated Successfully.');
    }

    public function deleteAd($id)
    {
        $ad = AdPosting::find($id);

        if (!$ad) {

            return redirect()->back()
                ->with('error', 'Ad not found.');
        }

        $ad->delete_status = 1;

        $ad->save();

        return redirect('admin.post.index')
            ->with('success', 'Advertisement deleted successfully.');
    }

    public function updateRemainingAds(Request $request)
    {
        $request->validate([

            'subscription_id' => 'required|exists:subscription_history,id',

            'remaining_ads' => 'required|integer|min:0',

        ]);

        $subscription = SubscriptionHistory::findOrFail(
            $request->subscription_id
        );

        // only free subscription editable
        if (in_array($subscription->type, ['Prime', 'Premium'])) {

            return back()->with(
                'error',
                'Paid subscriptions cannot be modified'
            );
        }

        $subscription->remaining_ads =
            $request->remaining_ads;

        $subscription->save();

        return back()->with(
            'success',
            'Remaining ads updated successfully'
        );
    }

}