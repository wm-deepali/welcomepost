<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ForgotPassword;
use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\RazorpayPaymentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\NotificationController;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Notifications\PushNotification;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|


| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('api/testuser1', [WebsiteController::class, 'testuser1']);
Route::get('/api/session-data', function (Request $request) {
    if ($request->session()->has('id')) {
        return response()->json(['session_id' => $request->session()->get('id')]);
    } else {
        return response()->json(['error' => 'No session data found'], 404);
    }
});
Route::post('api/fcm/update', [WebsiteController::class, 'updateFcmToken']);
Route::post('admin-chat/clear-all',[App\Http\Controllers\WebsiteController::class,'clearAllChat'])->name('admin.clear');
Route::get('api/login/{id}', function (Request $request,$id) {
    $request->session()->put('id',$id);
    return response()->json(['session_id' => $id]);
});
Route::post('order/process', [PaymentController::class,'orderProcess'])->name('order.process');
Route::get('order/status', [PaymentController::class,'orderStatus'])->name('order.status');
Route::get("getusername/{id}",[App\Http\Controllers\WebsiteController::class, 'getusername']);
Route::get('/pass',function(){
    echo Hash::make('12345678');
});
Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    return 'Application cache has been cleared';
});

Route::get('/route-cache', function() {
Artisan::call('route:cache');
    return 'Routes cache has been cleared';
});

Route::get('/config-cache', function() {
  Artisan::call('config:cache');
  return 'Config cache has been cleared';
}); 

Route::get('/view-clear', function() {
    Artisan::call('view:clear');
    return 'View cache has been cleared';
});

// Route::get('testcron', [App\Http\Controllers\GoogleController::class, 'testcron'])->name('testcron');

Route::post('/set-city-session', [App\Http\Controllers\WebsiteController::class, 'setCitySession'])->name('set-city-session');
Route::view('/temporary-redirect', 'temporaryRedirect')->name('temporary.redirect');
Route::get('mis-report', [App\Http\Controllers\AdminController::class,'mis_report'])->name('mis-report')->middleware('check.subadmin.permission:mis_report_edit');
Route::get('referral-link-report', [App\Http\Controllers\AdminController::class,'referral_link_report'])->name('referral-link-report')->middleware('check.subadmin.permission:mis_report_edit');
Route::get('mis-report/fail',function(){
    $data['subscription_order'] = App\Models\SubscriptionOrder::with('customer','subscription')->where('payment_status','Failed')->get();
    return view('admin.mis-reports.fail-transaction',$data);
})->middleware('check.subadmin.permission:mis_report_edit');
Route::get('mis-report/reserve',function(){
     $data['customer'] = Customer::with(['subscriptionhistory' => function ($query) {
            $query->orderByDesc('created_at');
        }, 'subscriptionhistory.subscriptions','countries','states','customerparent','customerchild','commission'])
        ->get();
     return view('admin.mis-reports.reserve-seeds',$data);
})->middleware('check.subadmin.permission:mis_report_edit');
Route::get('mis-report/active',function(){
    $data['customer'] = Customer::with(['subscriptionhistory' => function ($query) {
            $query->orderByDesc('created_at');
        }, 'subscriptionhistory.subscriptions','countries','states','customerparent','customerchild'])
        ->get();
     return view('admin.mis-reports.active-seeds',$data);
})->middleware('check.subadmin.permission:mis_report_edit');
Route::get('mis-report/user-income',function(){
     $data['commission'] = App\Models\CustomerCommission::with('customer.subscriptionhistory')->get();
     return view('admin.mis-reports.user-payouts',$data);
})->middleware('check.subadmin.permission:mis_report_edit');
Route::get('mis-report/daily-login',function(){
     return view('admin.mis-reports.daily-login');
})->middleware('check.subadmin.permission:mis_report_edit');
Route::get('mis-report/delete-account',function(){
       $data['customers'] = Customer::where('delete_status','1')->withTrashed()->orderby('id','desc')->get();
     return view('admin.mis-reports.delete-account',$data);
})->middleware('check.subadmin.permission:mis_report_edit');
Route::get('mis-report/block-user',function(){
    $data['block_user']        = DB::table('block_count')->get();
     return view('admin.mis-reports.block-user',$data);
})->middleware('check.subadmin.permission:mis_report_edit');
Route::get('mis-report/active-ad',function(){
    $data['active_ads']  = App\Models\Adposting::with('subscriptionhistory.customers','category','subcategory','ad_city')->where('active_status',1)->get();
     return view('admin.mis-reports.active-ad',$data);
})->middleware('check.subadmin.permission:mis_report_edit');
Route::get('mis-report/user-view',function(){
    $data['active_views']  = App\Models\Adposting::with('subscriptionhistory.customers')->where('active_status',1)->get();
     return view('admin.mis-reports.user-view',$data);
})->middleware('check.subadmin.permission:mis_report_edit');
Route::get('admin-login', [App\Http\Controllers\AdminController::class, 'index'])->name('admin-login');
Route::get('generate-pdf', [App\Http\Controllers\AdminController::class, 'generatePDF'])->name('generate-pdf');
Route::post('admin-login-post', [App\Http\Controllers\AdminController::class, 'login_post'])->name('admin-login-post');
Route::get('get-ad-images/{adId}', [App\Http\Controllers\WebsiteController::class, 'getAdImages']);

Route::middleware('changeMaintenanceMode')->group(function () {
    Route::post('chat-read', [App\Http\Controllers\WebsiteController::class, 'chat_read'])->name('chat-read');
    Route::post('/check-email', [App\Http\Controllers\WebsiteController::class, 'checkEmail'])->name('check-email');
    Route::post('/check-vverifyerify-email', [App\Http\Controllers\WebsiteController::class, 'checkVerifyEmail'])->name('check-verify-email');
    Route::post('/send-verify-link', [App\Http\Controllers\WebsiteController::class, 'sendVerifyLink'])->name('send-verify-link');
    Route::get('search-category',[App\Http\Controllers\WebsiteController::class,'searchCategories'])->name('search.categories');
    Route::get('login', [App\Http\Controllers\WebsiteController::class, 'login'])->name('login');
    Route::post('user-signup', [App\Http\Controllers\WebsiteController::class, 'user_signup'])->name('user-signup');
    Route::post('user-login', [App\Http\Controllers\WebsiteController::class, 'user_login'])->name('user-login');
    Route::post('login-with-mobile', [App\Http\Controllers\WebsiteController::class, 'login_with_mobile'])->name('login-with-mobile');
    Route::get('account/verify/{token}', [App\Http\Controllers\WebsiteController::class, 'verifyAccount'])->name('user.verify');
    Route::get('/', [App\Http\Controllers\WebsiteController::class, 'index'])->name('/');
    Route::get('purchase-subscription', [App\Http\Controllers\WebsiteController::class, 'purchase_subscription'])->name('purchase-subscription');
    Route::get('first/add-details', [App\Http\Controllers\WebsiteController::class, 'addRequiredDetails'])->name('first.details');
    Route::post('first/add-details/store', [App\Http\Controllers\WebsiteController::class, 'storeRequiredDetails'])->name('first.details.store');
    Route::post('post-chat-message', [App\Http\Controllers\WebsiteController::class, 'post_chat_message'])->name('post-chat-message');
    Route::group(['middleware' => 'customerCheck'], function () {
        Route::get('raise-ticket',[WebsiteController::class, 'raise_ticket'])->name('raise-ticket');
        Route::post('post-raise-ticket',[WebsiteController::class, 'post_raise_ticket'])->name('post.raise-ticket');
        Route::get('user-logout', [App\Http\Controllers\WebsiteController::class, 'user_logout'])->name('user-logout');
    });
    
    /*---------------  START WEBSITE PANEL -------------------*/
    Route::group(['middleware' => 'block'], function () {
        Route::get('blogs', [App\Http\Controllers\WebsiteController::class, 'blogs'])->name('blogs');
        Route::get('faqs', [App\Http\Controllers\WebsiteController::class, 'faqs'])->name('faqs');
        Route::get('contact', [App\Http\Controllers\WebsiteController::class, 'contact'])->name('contact');
        Route::get('pages/{id}/{url}', [App\Http\Controllers\WebsiteController::class, 'pages'])->name('pages');
        Route::get('abouts', [App\Http\Controllers\WebsiteController::class, 'abouts'])->name('abouts');
        Route::get('blog-details/{id}', [App\Http\Controllers\WebsiteController::class, 'blog_details'])->name('blog-details');
        Route::get('ads-details/{id}', [App\Http\Controllers\WebsiteController::class, 'ads_details'])->name('ads-details');
        Route::get('profile/{id}', [App\Http\Controllers\WebsiteController::class, 'profile'])->name('profile');
        Route::post('ads-enguiry', [App\Http\Controllers\WebsiteController::class, 'ads_enguiry'])->name('ads-enguiry');
        Route::post('ads-enguiry-reply', [App\Http\Controllers\WebsiteController::class, 'replyEnquiry'])->name('submit.enquiry.reply');
        Route::post('sendOtp',[App\Http\Controllers\WebsiteController::class,'sendOTP'])->name('mobileVerify');
        Route::post('/remove-welcome-amount', [App\Http\Controllers\WebsiteController::class, 'removeWelcomeAmount'])->name('removeWelcomeAmount');
        Route::post('verifyOTP',[App\Http\Controllers\WebsiteController::class,'verifyOTP'])->name('verifyOTP');
        Route::post('change-sendOtp',[App\Http\Controllers\WebsiteController::class,'sendOTPChange'])->name('change.mobileVerify');
        Route::post('change-verifyOTP',[App\Http\Controllers\WebsiteController::class,'verifyChangeMobileOTP'])->name('change.verifyOTP');
        Route::get('category-details/{id}', [App\Http\Controllers\WebsiteController::class, 'category_details'])->name('category-details');
        Route::get('category-ads/{id}', [App\Http\Controllers\WebsiteController::class, 'category_ads'])->name('category-ads');
        Route::get('subcategory-ads/{id}', [App\Http\Controllers\WebsiteController::class, 'subcategory_ads'])->name('subcategory-ads');
        Route::post('city-ads', [App\Http\Controllers\WebsiteController::class, 'city_ads'])->name('city-ads');
        Route::post('category-location-ads', [App\Http\Controllers\WebsiteController::class, 'category_location_ads'])->name('category-location-ads');
        Route::get('all-product',[App\Http\Controllers\WebsiteController::class, 'all_product'])->name('all-product');
        Route::get('auth/google', [App\Http\Controllers\GoogleController::class, 'redirectToGoogle'])->name('login.google');
        Route::get('/callback/google', [App\Http\Controllers\GoogleController::class, 'handleGoogleCallback']);
        Route::get('forget-password', [ForgotPassword::class, 'showForgetPasswordForm'])->name('forget.password.get');
        Route::post('forget-password', [ForgotPassword::class, 'submitForgetPasswordForm'])->name('forget.password.post'); 
        Route::get('reset-password/{token}', [ForgotPassword::class, 'showResetPasswordForm'])->name('reset.password.get');
        Route::post('reset-password', [ForgotPassword::class, 'submitResetPasswordForm'])->name('reset.password.post');
        Route::get('privacy-policy',[App\Http\Controllers\WebsiteController::class, 'privacy_policy'])->name('privacy-policy');
        Route::get('term-conditions',[App\Http\Controllers\WebsiteController::class, 'term_conditions'])->name('term-conditions');
        Route::post('get-state', [App\Http\Controllers\WebsiteController::class, 'get_state'])->name('get-state');
        Route::post('get-city', [App\Http\Controllers\WebsiteController::class, 'get_city'])->name('get-city');
        Route::post('cities-by-state', [App\Http\Controllers\WebsiteController::class, 'cities_by_state'])->name('cities-by-state');
        Route::post('get-location', [App\Http\Controllers\WebsiteController::class, 'get_location'])->name('get-location');
        Route::get('change-password',[App\Http\Controllers\WebsiteController::class, 'change_password'])->name('change-password');
        Route::get('top-search',[App\Http\Controllers\WebsiteController::class, 'top_search'])->name('top-search');
        Route::group(['middleware' => 'customerCheck'], function () {
            Route::get('post-ads', [App\Http\Controllers\WebsiteController::class, 'post_ads'])->name('post-ads');
            Route::get('/testuser', [App\Http\Controllers\WebsiteController::class, 'testuser'])->name('/testuser');
             Route::get('tickets', [App\Http\Controllers\WebsiteController::class, 'ticketIndex'])->name('tickets');
            Route::get('ad-forms/{formtype}/{catid}/{subcatid}', [App\Http\Controllers\WebsiteController::class, 'ad_forms'])->name('ad-forms');
            Route::post('post-job-form', [App\Http\Controllers\WebsiteController::class, 'post_job_form'])->name('post-job-form');
            Route::post('edit-post-job-form', [App\Http\Controllers\WebsiteController::class, 'edit_post_job_form'])->name('edit-post-job-form');
            Route::post('post-mobile-forms', [App\Http\Controllers\WebsiteController::class, 'post_mobile_forms'])->name('post-mobile-forms');
            Route::post('edit-post-mobile-forms', [App\Http\Controllers\WebsiteController::class, 'edit_post_mobile_forms'])->name('edit-post-mobile-forms');
            Route::post('post-vehicle-forms', [App\Http\Controllers\WebsiteController::class, 'post_vehicle_forms'])->name('post-vehicle-forms');
            Route::post('edit-post-vehicle-forms', [App\Http\Controllers\WebsiteController::class, 'edit_post_vehicle_forms'])->name('edit-post-vehicle-forms');
            Route::post('post-property-forms', [App\Http\Controllers\WebsiteController::class, 'post_property_forms'])->name('post-property-forms');
            Route::post('edit-post-property-forms', [App\Http\Controllers\WebsiteController::class, 'edit_post_property_forms'])->name('edit-post-property-forms');
            Route::post('post-common-forms', [App\Http\Controllers\WebsiteController::class, 'post_common_forms'])->name('post-common-forms');
            Route::post('edit-post-common-forms', [App\Http\Controllers\WebsiteController::class, 'edit_post_common_forms'])->name('edit-post-common-forms');
            Route::get('edit-ads/{formtype}/{catid}/{id}', [App\Http\Controllers\WebsiteController::class, 'edit_ads'])->name('edit-ads');
            Route::get('view-ads/{id}', [App\Http\Controllers\WebsiteController::class, 'view_ads'])->name('view-ads');
            Route::get('user-dashboard', [App\Http\Controllers\WebsiteController::class, 'user_dashboard'])->name('user-dashboard');
            Route::get('user-profile', [App\Http\Controllers\WebsiteController::class, 'user_profile'])->name('user-profile');
            Route::post('update-profile-account', [App\Http\Controllers\WebsiteController::class, 'user_profile_account'])->name('update-profile-account');
            Route::get('user-dash', [App\Http\Controllers\WebsiteController::class, 'user_dash'])->name('user-dash');
            Route::get('notification', [App\Http\Controllers\WebsiteController::class, 'notification'])->name('notification');
            Route::get('user-chat', [App\Http\Controllers\WebsiteController::class, 'user_chat'])->name('user-chat');
            Route::post('get-chat', [App\Http\Controllers\WebsiteController::class, 'get_chat'])->name('get-chat');
            Route::get('chat-with-us', [App\Http\Controllers\WebsiteController::class, 'admin_chat'])->name('chat-with-us');
            Route::post('chat-read', [App\Http\Controllers\WebsiteController::class, 'chat_read'])->name('chat-read');
            Route::post('clear-chat', [App\Http\Controllers\WebsiteController::class, 'clear_chat'])->name('clear-chat');
            Route::post('block-chat', [App\Http\Controllers\WebsiteController::class, 'block_chat'])->name('block-chat');
            Route::get('chat-with-owner/{id}/{userid}', [App\Http\Controllers\WebsiteController::class, 'chat_with_owner'])->name('chat-with-owner');
            Route::get('my-ads-enquiry', [App\Http\Controllers\WebsiteController::class, 'my_ads_enquiry'])->name('my-ads-enquiry');
            Route::get('owner-enquiry', [App\Http\Controllers\WebsiteController::class, 'owner_enquiry'])->name('owner-enquiry');
            
            
            Route::get('checkout/{id}', [App\Http\Controllers\WebsiteController::class, 'checkout'])->name('checkout');
            Route::post('razorpay-payment', [App\Http\Controllers\WebsiteController::class, 'razorpaystore'])->name('razorpay.payment.store');
            Route::post('free-subscription', [App\Http\Controllers\WebsiteController::class, 'free_subscription'])->name('free-subscription');
            Route::get('subscription-payment/{id}', [App\Http\Controllers\WebsiteController::class, 'subscription_payment'])->name('subscription-payment');
            Route::get('hide-email/{email}/{id}', [App\Http\Controllers\WebsiteController::class, 'hide_email'])->name('hide-email');
            Route::post('user-subscription-payment', [App\Http\Controllers\WebsiteController::class, 'user_subscription_payment'])->name('user-subscription-payment');
            Route::post('user-ads-payment', [App\Http\Controllers\WebsiteController::class, 'user_ads_payment'])->name('user-ads-payment');
            Route::get('expiry-data', [App\Http\Controllers\WebsiteController::class, 'expiry_data'])->name('expiry-data');
            Route::get('add-expire', [App\Http\Controllers\WebsiteController::class, 'add_expire'])->name('add-expire');
            Route::post('/change-email', [App\Http\Controllers\WebsiteController::class,'changeEmail'])->name('change.email');
            
            
        

            
            Route::get('thank-you', [App\Http\Controllers\WebsiteController::class, 'thank_you'])->name('thank-you');
            Route::post('user-update-password',[App\Http\Controllers\WebsiteController::class, 'user_pswd_change'])->name('user-update-password');
            Route::get('privacy-setting',[App\Http\Controllers\WebsiteController::class, 'privacy_setting'])->name('privacy-setting');
            Route::get('close-account',[App\Http\Controllers\WebsiteController::class, 'close_account'])->name('close-account');
            Route::post('deactivate-account',[App\Http\Controllers\WebsiteController::class, 'deactivate_account'])->name('deactivate-account');
            Route::get('logout-alldevice',[App\Http\Controllers\WebsiteController::class, 'logout_alldevice'])->name('logout-alldevice');
            Route::post('device-logout',[App\Http\Controllers\WebsiteController::class, 'device_logout'])->name('device-logout');
            Route::get('my-ads',[App\Http\Controllers\WebsiteController::class, 'my_ads'])->name('my-ads');
            Route::get('notifications',[App\Http\Controllers\WebsiteController::class, 'notifications'])->name('notifications');
            Route::get('my-orders',[App\Http\Controllers\WebsiteController::class, 'my_orders'])->name('my-orders');
            Route::get('view-subscriptionss/{id}',[App\Http\Controllers\WebsiteController::class, 'view_subscriptions'])->name('view-subscriptions');
            Route::get('view-auto-seeds-member/{id}',[App\Http\Controllers\WebsiteController::class, 'view_auto_seeds_member'])->name('view-auto-seeds-member');
            Route::delete('delete-ads/{id}',[App\Http\Controllers\WebsiteController::class, 'delete_ads'])->name('delete-ads');
            Route::post('get-my-ads',[App\Http\Controllers\WebsiteController::class, 'get_my_ads'])->name('get-my-ads');
            Route::post('get-my-subscription',[App\Http\Controllers\WebsiteController::class, 'get_my_subscription'])->name('get-my-subscription');
            
            Route::get('my-subscription',[App\Http\Controllers\WebsiteController::class, 'my_subscription'])->name('my-subscription');
            Route::get('my-team',[App\Http\Controllers\WebsiteController::class, 'my_team'])->name('my-team');
            Route::get('check-user-subscription',[App\Http\Controllers\WebsiteController::class, 'check_user_subscription'])->name('check-user-subscription');
            Route::get('get-ads-buy',[App\Http\Controllers\WebsiteController::class, 'get_ads_buy'])->name('get-ads-buy');
            Route::get('subscription-export/{id}',[App\Http\Controllers\WebsiteController::class, 'get_subscription_data'])->name('subscription.export');
            
            
            Route::get('help',[App\Http\Controllers\WebsiteController::class, 'help'])->name('help');
            Route::get('call-back',[WebsiteController::class, 'call_back'])->name('call-back');
            Route::post('post-call-back',[WebsiteController::class, 'post_call_back'])->name('post.call-back');
            
            Route::get('truncate', [App\Http\Controllers\WebsiteController::class, 'truncate'])->name('truncate');
            
            Route::post('send-enquiry',[App\Http\Controllers\WebsiteController::class, 'send_enquiry'])->name('send-enquiry');
            
            
            Route::get('user-side',[App\Http\Controllers\WebsiteController::class, 'user_side'])->name('user-side');
            
            Route::get('history',[App\Http\Controllers\WebsiteController::class, 'transaction_history'])->name('history');
            Route::get('my-earning',[App\Http\Controllers\WebsiteController::class, 'my_earning'])->name('my-earning');
            Route::get('my-referrals',[App\Http\Controllers\WebsiteController::class, 'my_referrals'])->name('my-referrals');
            Route::get('my-autojoining',[App\Http\Controllers\WebsiteController::class, 'myautojoining'])->name('my-autojoining');
            Route::get('user/payouts',[App\Http\Controllers\WebsiteController::class, 'payouts'])->name('user-payouts');
            //Route::get('my-team',[App\Http\Controllers\WebsiteController::class, 'Myteam'])->name('my-team');
            
            
            /*************************************** 
             * Wallet 
            ****************************************/
            Route::get('user-wallets', [App\Http\Controllers\WebsiteController::class, 'user_wallets'])->name('user-wallets');
            
            // Route::post('forgot-process',[App\Http\Controllers\WebsiteController::class, 'forgot_process'])->name('forgot-process');
            // Route::post('new-pswd',[App\Http\Controllers\WebsiteController::class, 'new_pswd'])->name('new-pswd');
            
            /****************************************
             *          END FORGOT
             * **************************************/
                
            
        });
    });
});




/*---------------  END WEBSITE PANEL -------------------*/


/*---------------  START ADMIN PANEL -------------------*/
Route::get('admin/forgot-password', [App\Http\Controllers\AdminController::class, 'showLinkRequestForm'])->name('admin.password.request');
Route::post('admin/forgot-password', [App\Http\Controllers\AdminController::class, 'sendResetLinkEmail'])->name('admin.password.email');
Route::get('admin/forgot-password/verify/{token}', [App\Http\Controllers\AdminController::class, 'forgetPasswordForm'])->name('admin.password.reset');
Route::post('admin/forgot-password/reset', [App\Http\Controllers\AdminController::class, 'submitForgetPassword'])->name('admin.password.submit');
Route::group(['middleware'=>['adminBasicAuth']],function(){

/*---------------  START ADMIN PROFILE -------------------*/

Route::get('admin-dashboard', [App\Http\Controllers\AdminController::class, 'admin_dashboard'])->name('admin-dashboard');
Route::get('customer-commission-export/{date}', [App\Http\Controllers\AdminController::class, 'commission_export'])->name('customer-commission-export');
Route::post('customer-commission-import', [App\Http\Controllers\AdminController::class, 'commission_import'])->name('customer-commission-import');
Route::get('admin-chat', [App\Http\Controllers\AdminController::class, 'admin_chat'])->name('admin-chat')->middleware('check.subadmin.permission:chat_edit');
Route::get('admin-profile', [App\Http\Controllers\AdminController::class, 'admin_profile'])->name('admin-profile');
Route::post('update-profile-details', [App\Http\Controllers\AdminController::class, 'update_profile_details'])->name('update-profile-details');
Route::post('update-profile-logo', [App\Http\Controllers\AdminController::class, 'update_profile_logo'])->name('update-profile-logo');
Route::post('update-profile-pic', [App\Http\Controllers\AdminController::class, 'update_profile_pic'])->name('update-profile-pic');
Route::post('check-mail-otp', [App\Http\Controllers\AdminController::class, 'check_mail_otp'])->name('check-mail-otp');
Route::get('otp-verification', [App\Http\Controllers\AdminController::class, 'otp_verification'])->name('otp-verification');
Route::post('otp-validate', [App\Http\Controllers\AdminController::class, 'otp_validate'])->name('otp-validate');
Route::post('change-password', [App\Http\Controllers\AdminController::class, 'change_password'])->name('change-password');

/*---------------  END ADMIN PROFILE -------------------*/

/*------------------  START USER -------------------*/

Route::get('user', [App\Http\Controllers\AdminController::class, 'user'])->name('user')->middleware('check.subadmin.permission:users_edit');
Route::get('deleted-user', [App\Http\Controllers\AdminController::class, 'deleteduser'])->name('deleted-user')->middleware('check.subadmin.permission:users_edit');
Route::get('add-user', [App\Http\Controllers\AdminController::class, 'add_user'])->name('add-user')->middleware('check.subadmin.permission:users_edit');
Route::post('post-add-user', [App\Http\Controllers\AdminController::class, 'post_add_user'])->name('post-add-user')->middleware('check.subadmin.permission:users_edit');
Route::get('delete-user/{id}', [App\Http\Controllers\AdminController::class, 'delete_user'])->name('delete-user')->middleware('check.subadmin.permission:users_edit');
Route::get('deletep-user/{id}', [App\Http\Controllers\AdminController::class, 'deletep_user'])->name('deletep-user')->middleware('check.subadmin.permission:users_edit');
Route::get('restore-user/{id}', [App\Http\Controllers\AdminController::class, 'restore_user'])->name('restore-user')->middleware('check.subadmin.permission:users_edit');
Route::get('edit-user/{id}', [App\Http\Controllers\AdminController::class, 'edit_user'])->name('edit-user')->middleware('check.subadmin.permission:users_edit');
Route::get('update-user/{id}', [App\Http\Controllers\AdminController::class, 'update_user'])->name('update-user')->middleware('check.subadmin.permission:users_edit');
Route::get('view-user/{id}', [App\Http\Controllers\AdminController::class, 'view_user'])->name('view-user')->middleware('check.subadmin.permission:users_edit');
Route::get('unblock-user/{id}', [App\Http\Controllers\AdminController::class, 'unblock_user'])->name('unblock-user')->middleware('check.subadmin.permission:users_edit');
Route::post('post-edit-user', [App\Http\Controllers\AdminController::class, 'post_edit_user'])->name('post-edit-user')->middleware('check.subadmin.permission:users_edit');

Route::get('add-users', [App\Http\Controllers\AdminController::class, 'add_users'])->name('add-users')->middleware('check.subadmin.permission:users_edit');

/*------------------  End USER -------------------*/


/*------------------  START CATEGORIES -------------------*/

Route::get('categories', [App\Http\Controllers\AdminController::class, 'categories'])->name('categories')->middleware('check.subadmin.permission:master_edit');
Route::get('add-categories', [App\Http\Controllers\AdminController::class, 'add_categories'])->name('add-categories')->middleware('check.subadmin.permission:master_edit');
Route::post('post-add-categories', [App\Http\Controllers\AdminController::class, 'post_add_categories'])->name('post-add-categories')->middleware('check.subadmin.permission:master_edit');
Route::get('view-categories/{id}', [App\Http\Controllers\AdminController::class, 'view_categories'])->name('view-categories')->middleware('check.subadmin.permission:master_edit');
Route::post('post-edit-categories', [App\Http\Controllers\AdminController::class, 'post_edit_categories'])->name('post-edit-categories')->middleware('check.subadmin.permission:master_edit');
Route::get('delete-categories/{id}', [App\Http\Controllers\AdminController::class, 'delete_categories'])->name('delete-categories')->middleware('check.subadmin.permission:master_edit');
Route::get('update-status/{id}/{status}', [App\Http\Controllers\AdminController::class, 'update_status'])->name('update-status')->middleware('check.subadmin.permission:master_edit');
Route::get('update-premium/{id}/{premium}', [App\Http\Controllers\AdminController::class, 'update_premium'])->name('update-premium')->middleware('check.subadmin.permission:master_edit');
Route::get('update-top/{id}/{top}', [App\Http\Controllers\AdminController::class, 'update_top'])->name('update-top')->middleware('check.subadmin.permission:master_edit');
Route::get('update-trending/{id}/{trending}', [App\Http\Controllers\AdminController::class, 'update_trending'])->name('update-trending')->middleware('check.subadmin.permission:master_edit');
Route::get('view-form/{id}', [App\Http\Controllers\AdminController::class, 'view_form'])->name('view-form')->middleware('check.subadmin.permission:master_edit');

/*------------------  END CATEGORIES -------------------*/

/*------------------  START SUB CATEGORIES -------------------*/

Route::get('sub-categories', [App\Http\Controllers\AdminController::class, 'sub_categories'])->name('sub-categories')->middleware('check.subadmin.permission:master_edit');
Route::get('add-subcategories', [App\Http\Controllers\AdminController::class, 'add_subcategories'])->name('add-subcategories')->middleware('check.subadmin.permission:master_edit');
Route::post('post-add-subcategories', [App\Http\Controllers\AdminController::class, 'post_add_subcategories'])->name('post-add-subcategories')->middleware('check.subadmin.permission:master_edit');
Route::get('delete-subcategories/{id}', [App\Http\Controllers\AdminController::class, 'delete_subcategories'])->name('delete-subcategories')->middleware('check.subadmin.permission:master_edit');
Route::get('edit-subcategories/{id}', [App\Http\Controllers\AdminController::class, 'edit_subcategories'])->name('edit-subcategories')->middleware('check.subadmin.permission:master_edit');
Route::post('post-edit-subcategories', [App\Http\Controllers\AdminController::class, 'post_edit_subcategories'])->name('post-edit-subcategories')->middleware('check.subadmin.permission:master_edit');

Route::get('view-subcategories/{id}', [App\Http\Controllers\AdminController::class, 'view_subcategories'])->name('view-subcategories')->middleware('check.subadmin.permission:master_edit');

/*------------------  End SUB CATEGORIES -------------------*/


/*------------------  START COUNTRIES -------------------*/

Route::get('countries', [App\Http\Controllers\AdminController::class, 'countries'])->name('countries')->middleware('check.subadmin.permission:master_edit');
Route::get('add-countries', [App\Http\Controllers\AdminController::class, 'add_countries'])->name('add-countries')->middleware('check.subadmin.permission:master_edit');
Route::post('post-add-countries', [App\Http\Controllers\AdminController::class, 'post_add_countries'])->name('post-add-countries')->middleware('check.subadmin.permission:master_edit');
Route::get('delete-countries/{id}', [App\Http\Controllers\AdminController::class, 'delete_countries'])->name('delete-countries')->middleware('check.subadmin.permission:master_edit');
Route::get('edit-countries/{id}', [App\Http\Controllers\AdminController::class, 'edit_countries'])->name('edit-countries')->middleware('check.subadmin.permission:master_edit');
Route::post('post-edit-countries', [App\Http\Controllers\AdminController::class, 'post_edit_countries'])->name('post-edit-countries')->middleware('check.subadmin.permission:master_edit');


/*------------------  End COUNTRIES -------------------*/


/*------------------  START STATES -------------------*/

Route::get('states', [App\Http\Controllers\AdminController::class, 'states'])->name('states')->middleware('check.subadmin.permission:master_edit');
Route::get('add-states', [App\Http\Controllers\AdminController::class, 'add_states'])->name('add-states')->middleware('check.subadmin.permission:master_edit');
Route::post('post-add-states', [App\Http\Controllers\AdminController::class, 'post_add_states'])->name('post-add-states')->middleware('check.subadmin.permission:master_edit');
Route::get('delete-states/{id}', [App\Http\Controllers\AdminController::class, 'delete_states'])->name('delete-states')->middleware('check.subadmin.permission:master_edit');
Route::get('edit-states/{id}', [App\Http\Controllers\AdminController::class, 'edit_states'])->name('edit-states')->middleware('check.subadmin.permission:master_edit');
Route::post('post-edit-states', [App\Http\Controllers\AdminController::class, 'post_edit_states'])->name('post-edit-states')->middleware('check.subadmin.permission:master_edit');


/*------------------  End STATES -------------------*/

/*------------------  START CITIES -------------------*/

Route::get('city', [App\Http\Controllers\AdminController::class, 'city'])->name('city')->middleware('check.subadmin.permission:master_edit');
Route::get('add-city', [App\Http\Controllers\AdminController::class, 'add_city'])->name('add-city')->middleware('check.subadmin.permission:master_edit');
Route::post('post-add-city', [App\Http\Controllers\AdminController::class, 'post_add_city'])->name('post-add-city')->middleware('check.subadmin.permission:master_edit');
Route::get('delete-city/{id}', [App\Http\Controllers\AdminController::class, 'delete_city'])->name('delete-city')->middleware('check.subadmin.permission:master_edit');
Route::get('edit-city/{id}', [App\Http\Controllers\AdminController::class, 'edit_city'])->name('edit-city')->middleware('check.subadmin.permission:master_edit');
Route::post('post-edit-city', [App\Http\Controllers\AdminController::class, 'post_edit_city'])->name('post-edit-city')->middleware('check.subadmin.permission:master_edit');


/*------------------  End CITIES -------------------*/


/*------------------  START ZIP -------------------*/

Route::get('zip', [App\Http\Controllers\AdminController::class, 'zip'])->name('zip')->middleware('check.subadmin.permission:master_edit');
Route::get('add-zip', [App\Http\Controllers\AdminController::class, 'add_zip'])->name('add-zip')->middleware('check.subadmin.permission:master_edit');
Route::post('post-add-zip', [App\Http\Controllers\AdminController::class, 'post_add_zip'])->name('post-add-zip')->middleware('check.subadmin.permission:master_edit');
Route::get('delete-zip/{id}', [App\Http\Controllers\AdminController::class, 'delete_zip'])->name('delete-zip')->middleware('check.subadmin.permission:master_edit');
Route::get('edit-zip/{id}', [App\Http\Controllers\AdminController::class, 'edit_zip'])->name('edit-zip')->middleware('check.subadmin.permission:master_edit');
Route::post('post-edit-zip', [App\Http\Controllers\AdminController::class, 'post_edit_zip'])->name('post-edit-zip')->middleware('check.subadmin.permission:master_edit');


/*------------------  End ZIP -------------------*/

/*------------------  START LOCATION -------------------*/

Route::get('location', [App\Http\Controllers\AdminController::class, 'location'])->name('location')->middleware('check.subadmin.permission:master_edit');
Route::get('add-location', [App\Http\Controllers\AdminController::class, 'add_location'])->name('add-location')->middleware('check.subadmin.permission:master_edit');
Route::post('post-add-location', [App\Http\Controllers\AdminController::class, 'post_add_location'])->name('post-add-location')->middleware('check.subadmin.permission:master_edit');
Route::get('delete-location/{id}', [App\Http\Controllers\AdminController::class, 'delete_location'])->name('delete-location')->middleware('check.subadmin.permission:master_edit');
Route::get('edit-location/{id}', [App\Http\Controllers\AdminController::class, 'edit_location'])->name('edit-location')->middleware('check.subadmin.permission:master_edit');
Route::post('post-edit-location', [App\Http\Controllers\AdminController::class, 'post_edit_location'])->name('post-edit-location')->middleware('check.subadmin.permission:master_edit');


Route::post('/toggle-maintenance', [App\Http\Controllers\AdminController::class, 'toggleMaintenance'])->name('toggleMaintenance');
Route::get('admin/user/unlock/{id}',[App\Http\Controllers\AdminController::class,'unlockUserAccount'])->name('admin.unlock.account');

/*------------------  End LOCATION -------------------*/


/*------------------  START BRAND -------------------*/

Route::get('brand', [App\Http\Controllers\AdminController::class, 'brand'])->name('brand')->middleware('check.subadmin.permission:master_edit');
Route::get('add-brand', [App\Http\Controllers\AdminController::class, 'add_brand'])->name('add-brand')->middleware('check.subadmin.permission:master_edit');
Route::post('post-add-brand', [App\Http\Controllers\AdminController::class, 'post_add_brand'])->name('post-add-brand')->middleware('check.subadmin.permission:master_edit');
Route::get('delete-brand/{id}', [App\Http\Controllers\AdminController::class, 'delete_brand'])->name('delete-brand')->middleware('check.subadmin.permission:master_edit');
Route::get('edit-brand/{id}', [App\Http\Controllers\AdminController::class, 'edit_brand'])->name('edit-brand')->middleware('check.subadmin.permission:master_edit');
Route::post('post-edit-brand', [App\Http\Controllers\AdminController::class, 'post_edit_brand'])->name('post-edit-brand')->middleware('check.subadmin.permission:master_edit');


/*------------------  End BRAND -------------------*/


/*------------------  VEHICLE TYPES -------------------*/

Route::get('vehicletypes', [App\Http\Controllers\AdminController::class, 'vehicletypes'])->name('vehicletypes')->middleware('check.subadmin.permission:master_edit');
Route::get('add-vehicletypes', [App\Http\Controllers\AdminController::class, 'add_vehicletypes'])->name('add-vehicletypes')->middleware('check.subadmin.permission:master_edit');
Route::post('post-add-vehicletypes', [App\Http\Controllers\AdminController::class, 'post_add_vehicletypes'])->name('post-add-vehicletypes')->middleware('check.subadmin.permission:master_edit');
Route::get('delete-vehicletypes/{id}', [App\Http\Controllers\AdminController::class, 'delete_vehicletypes'])->name('delete-vehicletypes')->middleware('check.subadmin.permission:master_edit');
Route::get('edit-vehicletypes/{id}', [App\Http\Controllers\AdminController::class, 'edit_vehicletypes'])->name('edit-vehicletypes')->middleware('check.subadmin.permission:master_edit');
Route::post('post-edit-vehicletypes', [App\Http\Controllers\AdminController::class, 'post_edit_vehicletypes'])->name('post-edit-vehicletypes')->middleware('check.subadmin.permission:master_edit');


/*------------------  VEHICLE TYPES -------------------*/


/*------------------  FUEL TYPES -------------------*/

Route::get('fueltype', [App\Http\Controllers\AdminController::class, 'fueltype'])->name('fueltype')->middleware('check.subadmin.permission:master_edit');
Route::get('add-fueltype', [App\Http\Controllers\AdminController::class, 'add_fueltype'])->name('add-fueltype')->middleware('check.subadmin.permission:master_edit');
Route::post('post-add-fueltype', [App\Http\Controllers\AdminController::class, 'post_add_fueltype'])->name('post-add-fueltype')->middleware('check.subadmin.permission:master_edit');
Route::get('delete-fueltype/{id}', [App\Http\Controllers\AdminController::class, 'delete_fueltype'])->name('delete-fueltype')->middleware('check.subadmin.permission:master_edit');
Route::get('edit-fueltype/{id}', [App\Http\Controllers\AdminController::class, 'edit_fueltype'])->name('edit-fueltype')->middleware('check.subadmin.permission:master_edit');
Route::post('post-edit-fueltype', [App\Http\Controllers\AdminController::class, 'post_edit_fueltype'])->name('post-edit-fueltype')->middleware('check.subadmin.permission:master_edit');


/*------------------  FUEL TYPES -------------------*/

/*------------------  TRANSMISSION MANAGEMENT -------------------*/

Route::get('transmission', [App\Http\Controllers\AdminController::class, 'transmission'])->name('transmission')->middleware('check.subadmin.permission:master_edit');
Route::get('add-transmission', [App\Http\Controllers\AdminController::class, 'add_transmission'])->name('add-transmission')->middleware('check.subadmin.permission:master_edit');
Route::post('post-add-transmission', [App\Http\Controllers\AdminController::class, 'post_add_transmission'])->name('post-add-transmission')->middleware('check.subadmin.permission:master_edit');
Route::get('delete-transmission/{id}', [App\Http\Controllers\AdminController::class, 'delete_transmission'])->name('delete-transmission')->middleware('check.subadmin.permission:master_edit');
Route::get('edit-transmission/{id}', [App\Http\Controllers\AdminController::class, 'edit_transmission'])->name('edit-transmission')->middleware('check.subadmin.permission:master_edit');
Route::post('post-edit-transmission', [App\Http\Controllers\AdminController::class, 'post_edit_transmission'])->name('post-edit-transmission')->middleware('check.subadmin.permission:master_edit');


/*------------------  TRANSMISSION MANAGEMENT -------------------*/

/*------------------  RESIDENCE TYPE MANAGEMENT -------------------*/

Route::get('residencetype', [App\Http\Controllers\AdminController::class, 'residencetype'])->name('residencetype')->middleware('check.subadmin.permission:master_edit');
Route::get('add-residencetype', [App\Http\Controllers\AdminController::class, 'add_residencetype'])->name('add-residencetype')->middleware('check.subadmin.permission:master_edit');
Route::post('post-add-residencetype', [App\Http\Controllers\AdminController::class, 'post_add_residencetype'])->name('post-add-residencetype')->middleware('check.subadmin.permission:master_edit');
Route::get('delete-residencetype/{id}', [App\Http\Controllers\AdminController::class, 'delete_residencetype'])->name('delete-residencetype')->middleware('check.subadmin.permission:master_edit');
Route::get('edit-residencetype/{id}', [App\Http\Controllers\AdminController::class, 'edit_residencetype'])->name('edit-residencetype')->middleware('check.subadmin.permission:master_edit');
Route::post('post-edit-residencetype', [App\Http\Controllers\AdminController::class, 'post_edit_residencetype'])->name('post-edit-residencetype')->middleware('check.subadmin.permission:master_edit');


/*------------------  RESIDENCE TYPE MANAGEMENT -------------------*/


/*------------------  FURNISHING MANAGEMENT -------------------*/

Route::get('furnishingtype', [App\Http\Controllers\AdminController::class, 'furnishingtype'])->name('furnishingtype')->middleware('check.subadmin.permission:master_edit');
Route::get('add-furnishingtype', [App\Http\Controllers\AdminController::class, 'add_furnishingtype'])->name('add-furnishingtype')->middleware('check.subadmin.permission:master_edit');
Route::post('post-add-furnishingtype', [App\Http\Controllers\AdminController::class, 'post_add_furnishingtype'])->name('post-add-furnishingtype')->middleware('check.subadmin.permission:master_edit');
Route::get('delete-furnishingtype/{id}', [App\Http\Controllers\AdminController::class, 'delete_furnishingtype'])->name('delete-furnishingtype')->middleware('check.subadmin.permission:master_edit');
Route::get('edit-furnishingtype/{id}', [App\Http\Controllers\AdminController::class, 'edit_furnishingtype'])->name('edit-furnishingtype')->middleware('check.subadmin.permission:master_edit');
Route::post('post-edit-furnishingtype', [App\Http\Controllers\AdminController::class, 'post_edit_furnishingtype'])->name('post-edit-furnishingtype')->middleware('check.subadmin.permission:master_edit');


/*------------------  FURNISHING MANAGEMENT -------------------*/


/*------------------  FURNISHING MANAGEMENT -------------------*/

Route::get('construction', [App\Http\Controllers\AdminController::class, 'construction'])->name('construction')->middleware('check.subadmin.permission:master_edit');
Route::get('add-construction', [App\Http\Controllers\AdminController::class, 'add_construction'])->name('add-construction')->middleware('check.subadmin.permission:master_edit');
Route::post('post-add-construction', [App\Http\Controllers\AdminController::class, 'post_add_construction'])->name('post-add-construction')->middleware('check.subadmin.permission:master_edit');
Route::get('delete-construction/{id}', [App\Http\Controllers\AdminController::class, 'delete_construction'])->name('delete-construction')->middleware('check.subadmin.permission:master_edit');
Route::get('edit-construction/{id}', [App\Http\Controllers\AdminController::class, 'edit_construction'])->name('edit-construction')->middleware('check.subadmin.permission:master_edit');
Route::post('post-edit-construction', [App\Http\Controllers\AdminController::class, 'post_edit_construction'])->name('post-edit-construction')->middleware('check.subadmin.permission:master_edit');


/*------------------  FURNISHING MANAGEMENT -------------------*/


/*------------------  FACING MANAGEMENT -------------------*/

Route::get('facing', [App\Http\Controllers\AdminController::class, 'facing'])->name('facing')->middleware('check.subadmin.permission:master_edit');
Route::get('add-facing', [App\Http\Controllers\AdminController::class, 'add_facing'])->name('add-facing')->middleware('check.subadmin.permission:master_edit');
Route::post('post-add-facing', [App\Http\Controllers\AdminController::class, 'post_add_facing'])->name('post-add-facing')->middleware('check.subadmin.permission:master_edit');
Route::get('delete-facing/{id}', [App\Http\Controllers\AdminController::class, 'delete_facing'])->name('delete-facing')->middleware('check.subadmin.permission:master_edit');
Route::get('edit-facing/{id}', [App\Http\Controllers\AdminController::class, 'edit_facing'])->name('edit-facing')->middleware('check.subadmin.permission:master_edit');
Route::post('post-edit-facing', [App\Http\Controllers\AdminController::class, 'post_edit_facing'])->name('post-edit-facing')->middleware('check.subadmin.permission:master_edit');


/*------------------  FACING MANAGEMENT -------------------*/


/*------------------  JOB MANAGEMENT -------------------*/

Route::get('job', [App\Http\Controllers\AdminController::class, 'job'])->name('job')->middleware('check.subadmin.permission:master_edit');
Route::get('add-job', [App\Http\Controllers\AdminController::class, 'add_job'])->name('add-job')->middleware('check.subadmin.permission:master_edit');
Route::post('post-add-job', [App\Http\Controllers\AdminController::class, 'post_add_job'])->name('post-add-job')->middleware('check.subadmin.permission:master_edit');
Route::get('delete-job/{id}', [App\Http\Controllers\AdminController::class, 'delete_job'])->name('delete-job')->middleware('check.subadmin.permission:master_edit');
Route::get('edit-job/{id}', [App\Http\Controllers\AdminController::class, 'edit_job'])->name('edit-job')->middleware('check.subadmin.permission:master_edit');
Route::post('post-edit-job', [App\Http\Controllers\AdminController::class, 'post_edit_job'])->name('post-edit-job')->middleware('check.subadmin.permission:master_edit');


/*------------------  JOB MANAGEMENT -------------------*/


/*------------------  SUBSCRIPTION MANAGEMENT -------------------*/

Route::get('subscription', [App\Http\Controllers\AdminController::class, 'subscription'])->name('subscription')->middleware('check.subadmin.permission:subscription_edit');
Route::get('add-subscription', [App\Http\Controllers\AdminController::class, 'add_subscription'])->name('add-subscription')->middleware('check.subadmin.permission:subscription_edit');
Route::post('post-add-subscription', [App\Http\Controllers\AdminController::class, 'post_add_subscription'])->name('post-add-subscription')->middleware('check.subadmin.permission:subscription_edit');
Route::get('delete-subscription/{id}', [App\Http\Controllers\AdminController::class, 'delete_subscription'])->name('delete-subscription')->middleware('check.subadmin.permission:subscription_edit');
Route::get('edit-subscription/{id}', [App\Http\Controllers\AdminController::class, 'edit_subscription'])->name('edit-subscription')->middleware('check.subadmin.permission:subscription_edit');
Route::post('post-edit-subscription', [App\Http\Controllers\AdminController::class, 'post_edit_subscription'])->name('post-edit-subscription')->middleware('check.subadmin.permission:subscription_edit');
Route::get('status-update-subscription/{id}', [App\Http\Controllers\AdminController::class, 'update_status_subscriber'])->name('status-update-subscription')->middleware('check.subadmin.permission:subscription_edit');


/*------------------  SUBSCRIPTION MANAGEMENT -------------------*/

/*------------------  SUBSCRIPTION MANAGEMENT -------------------*/

Route::get('per-ads-costing', [App\Http\Controllers\AdminController::class, 'per_ads_costing'])->name('per-ads-costing')->middleware('check.subadmin.permission:subscription_edit');
Route::post('post-ads-costing', [App\Http\Controllers\AdminController::class, 'post_ads_costing'])->name('post-ads-costing')->middleware('check.subadmin.permission:subscription_edit');
Route::get('admin-subscription-export/{id}',[App\Http\Controllers\AdminController::class, 'get_subscription_data'])->name('admin.subscription.export')->middleware('check.subadmin.permission:subscription_edit');

/*------------------  SUBSCRIPTION MANAGEMENT -------------------*/


/*------------------  FREE TRAIL SUBSCRIPTION  MANAGEMENT -------------------*/

Route::get('freetrail', [App\Http\Controllers\AdminController::class, 'freetrail'])->name('freetrail')->middleware('check.subadmin.permission:subscription_edit');
Route::get('add-freetrail', [App\Http\Controllers\AdminController::class, 'add_freetrail'])->name('add-freetrail')->middleware('check.subadmin.permission:subscription_edit');
Route::post('post-add-freetrail', [App\Http\Controllers\AdminController::class, 'post_add_freetrail'])->name('post-add-freetrail')->middleware('check.subadmin.permission:subscription_edit');
Route::get('delete-freetrail/{id}', [App\Http\Controllers\AdminController::class, 'delete_freetrail'])->name('delete-freetrail')->middleware('check.subadmin.permission:subscription_edit');
Route::get('edit-freetrail/{id}', [App\Http\Controllers\AdminController::class, 'edit_freetrail'])->name('edit-freetrail')->middleware('check.subadmin.permission:subscription_edit');
Route::post('post-edit-freetrail', [App\Http\Controllers\AdminController::class, 'post_edit_freetrail'])->name('post-edit-freetrail')->middleware('check.subadmin.permission:subscription_edit');


/*------------------  FREE TRAIL SUBSCRIPTION  MANAGEMENT -------------------*/

/*------------------  BLOG MANAGEMENT -------------------*/

Route::get('blog', [App\Http\Controllers\AdminController::class, 'blog'])->name('blog')->middleware('check.subadmin.permission:content_edit');
Route::get('add-blog', [App\Http\Controllers\AdminController::class, 'add_blog'])->name('add-blog')->middleware('check.subadmin.permission:content_edit');
Route::post('post-add-blog', [App\Http\Controllers\AdminController::class, 'post_add_blog'])->name('post-add-blog')->middleware('check.subadmin.permission:content_edit');
Route::get('delete-blog/{id}', [App\Http\Controllers\AdminController::class, 'delete_blog'])->name('delete-blog')->middleware('check.subadmin.permission:content_edit');
Route::get('edit-blog/{id}', [App\Http\Controllers\AdminController::class, 'edit_blog'])->name('edit-blog')->middleware('check.subadmin.permission:content_edit');
Route::post('post-edit-blog', [App\Http\Controllers\AdminController::class, 'post_edit_blog'])->name('post-edit-blog')->middleware('check.subadmin.permission:content_edit');


/*------------------  BLOG MANAGEMENT -------------------*/


/*---------------  START CONTACT US  -------------------*/

Route::get('contact-details', [App\Http\Controllers\AdminController::class, 'contact_details'])->name('contact-details')->middleware('check.subadmin.permission:content_edit');
Route::post('update-contact-details', [App\Http\Controllers\AdminController::class, 'update_contact_details'])->name('update-contact-details')->middleware('check.subadmin.permission:content_edit');
Route::post('update-contact-logo', [App\Http\Controllers\AdminController::class, 'update_contact_logo'])->name('update-contact-logo')->middleware('check.subadmin.permission:content_edit');

/*---------------  END CONTACT US  -------------------*/


/*------------------  START FAQ CATEGORIES -------------------*/

Route::get('faqcategory', [App\Http\Controllers\AdminController::class, 'faqcategory'])->name('faqcategory')->middleware('check.subadmin.permission:content_edit');
Route::get('add-faqcategory', [App\Http\Controllers\AdminController::class, 'add_faqcategory'])->name('add-faqcategory')->middleware('check.subadmin.permission:content_edit');
Route::post('post-add-faqcategory', [App\Http\Controllers\AdminController::class, 'post_add_faqcategory'])->name('post-add-faqcategory')->middleware('check.subadmin.permission:content_edit');
Route::get('edit-faqcategory/{id}', [App\Http\Controllers\AdminController::class, 'edit_faqcategory'])->name('edit-faqcategory')->middleware('check.subadmin.permission:content_edit');
Route::post('post-edit-faqcategory', [App\Http\Controllers\AdminController::class, 'post_edit_faqcategory'])->name('post-edit-faqcategory')->middleware('check.subadmin.permission:content_edit');
Route::get('delete-faqcategory/{id}', [App\Http\Controllers\AdminController::class, 'delete_faqcategory'])->name('delete-faqcategory')->middleware('check.subadmin.permission:content_edit');

/*------------------  END FAQ CATEGORIES -------------------*/

/*------------------  START FAQ -------------------*/

Route::get('faq', [App\Http\Controllers\AdminController::class, 'faq'])->name('faq')->middleware('check.subadmin.permission:content_edit');
Route::get('add-faq', [App\Http\Controllers\AdminController::class, 'add_faq'])->name('add-faq')->middleware('check.subadmin.permission:content_edit');
Route::post('post-add-faq', [App\Http\Controllers\AdminController::class, 'post_add_faq'])->name('post-add-faq')->middleware('check.subadmin.permission:content_edit');
Route::get('edit-faq/{id}', [App\Http\Controllers\AdminController::class, 'edit_faq'])->name('edit-faq')->middleware('check.subadmin.permission:content_edit');
Route::post('post-edit-faq', [App\Http\Controllers\AdminController::class, 'post_edit_faq'])->name('post-edit-faq')->middleware('check.subadmin.permission:content_edit');
Route::get('delete-faq/{id}', [App\Http\Controllers\AdminController::class, 'delete_faq'])->name('delete-faq')->middleware('check.subadmin.permission:content_edit');

/*------------------  END FAQ -------------------*/
Route::get('admin/banner', [App\Http\Controllers\AdminController::class, 'bannerIndex'])->name('banner.index')->middleware('check.subadmin.permission:content_edit');
Route::get('admin/banner/create', [App\Http\Controllers\AdminController::class, 'bannerCreate'])->name('banner.create')->middleware('check.subadmin.permission:content_edit');
Route::post('admin/banner/store', [App\Http\Controllers\AdminController::class, 'bannerStore'])->name('banner.store')->middleware('check.subadmin.permission:content_edit');

Route::get('admin/banner/edit/{id}', [App\Http\Controllers\AdminController::class, 'bannerEdit'])->name('banner.edit')->middleware('check.subadmin.permission:content_edit');
Route::post('admin/banner/update/{id}', [App\Http\Controllers\AdminController::class, 'bannerUpdate'])->name('banner.update')->middleware('check.subadmin.permission:content_edit');

Route::delete('admin/banner/destroy/{id}', [App\Http\Controllers\AdminController::class, 'bannerDelete'])->name('banner.destroy')->middleware('check.subadmin.permission:content_edit');

Route::get('admin/footer-settings', [App\Http\Controllers\AdminController::class, 'footerIndex'])->name('footer.setting')->middleware('check.subadmin.permission:content_edit');
Route::post('admin/footer-settings/store', [App\Http\Controllers\AdminController::class, 'footerStore'])->name('footer.store')->middleware('check.subadmin.permission:content_edit');
/*------------------  START ABOUT MANAGEMENT -------------------*/

Route::get('about', [App\Http\Controllers\AdminController::class, 'about'])->name('about')->middleware('check.subadmin.permission:content_edit');
Route::get('add-about', [App\Http\Controllers\AdminController::class, 'add_about'])->name('add-about')->middleware('check.subadmin.permission:content_edit');
Route::post('post-add-about', [App\Http\Controllers\AdminController::class, 'post_add_about'])->name('post-add-about')->middleware('check.subadmin.permission:content_edit');
Route::get('edit-about/{id}', [App\Http\Controllers\AdminController::class, 'edit_about'])->name('edit-about')->middleware('check.subadmin.permission:content_edit');
Route::post('post-edit-about', [App\Http\Controllers\AdminController::class, 'post_edit_about'])->name('post-edit-about')->middleware('check.subadmin.permission:content_edit');
Route::get('delete-about/{id}', [App\Http\Controllers\AdminController::class, 'delete_about'])->name('delete-about')->middleware('check.subadmin.permission:content_edit');

/*------------------  END  ABOUT MANAGEMENT -------------------*/


/*------------------  START pages MANAGEMENT -------------------*/

Route::get('pages', [App\Http\Controllers\AdminController::class, 'pages'])->name('pages')->middleware('check.subadmin.permission:content_edit');
Route::get('add-pages', [App\Http\Controllers\AdminController::class, 'add_pages'])->name('add-pages')->middleware('check.subadmin.permission:content_edit');
Route::post('post-add-pages', [App\Http\Controllers\AdminController::class, 'post_add_pages'])->name('post-add-pages')->middleware('check.subadmin.permission:content_edit');
Route::get('edit-pages/{id}', [App\Http\Controllers\AdminController::class, 'edit_pages'])->name('edit-pages')->middleware('check.subadmin.permission:content_edit');
Route::post('post-edit-pages', [App\Http\Controllers\AdminController::class, 'post_edit_pages'])->name('post-edit-pages')->middleware('check.subadmin.permission:content_edit');
Route::get('delete-pages/{id}', [App\Http\Controllers\AdminController::class, 'delete_pages'])->name('delete-pages')->middleware('check.subadmin.permission:content_edit');

/*------------------  END  pages MANAGEMENT -------------------*/


/*------------------  START FORM TYPE -----------------*/

Route::get('formtype', [App\Http\Controllers\AdminController::class, 'formtype'])->name('formtype');
Route::get('add-formtype', [App\Http\Controllers\AdminController::class, 'add_formtype'])->name('add-formtype');
Route::post('post-add-formtype', [App\Http\Controllers\AdminController::class, 'post_add_formtype'])->name('post-add-formtype');
Route::get('delete-formtype/{id}', [App\Http\Controllers\AdminController::class, 'delete_formtype'])->name('delete-formtype');
Route::get('edit-formtype/{id}', [App\Http\Controllers\AdminController::class, 'edit_formtype'])->name('edit-formtype');
Route::post('post-edit-formtype', [App\Http\Controllers\AdminController::class, 'post_edit_formtype'])->name('post-edit-formtype');


/*------------------  End FORM TYPE -------------------*/


/*------------------  START JOB ADS -------------------*/

Route::get('ads', [App\Http\Controllers\AdminController::class, 'ads'])->name('ads')->middleware('check.subadmin.permission:ads_edit');
Route::get('job-ads', [App\Http\Controllers\AdminController::class, 'job_ads'])->name('job_ads')->middleware('check.subadmin.permission:ads_edit');
Route::get('published-job-ads', [App\Http\Controllers\AdminController::class, 'published_job_ads'])->name('published-job-ads')->middleware('check.subadmin.permission:ads_edit');
Route::get('rejected-job-ads', [App\Http\Controllers\AdminController::class, 'rejected_job_ads'])->name('rejected-job-ads')->middleware('check.subadmin.permission:ads_edit');
Route::get('delete-job-ads/{id}', [App\Http\Controllers\AdminController::class, 'delete_job_ads'])->name('delete-job-ads')->middleware('check.subadmin.permission:ads_edit');
Route::get('edit-job-ads/{id}', [App\Http\Controllers\AdminController::class, 'edit_job_ads'])->name('edit-job-ads')->middleware('check.subadmin.permission:ads_edit');
Route::post('post-edit-job-ads', [App\Http\Controllers\AdminController::class, 'post_edit_job_ads'])->name('post-edit-job-ads')->middleware('check.subadmin.permission:ads_edit');
Route::get('update-job-ad-status/{id}/{status}', [App\Http\Controllers\AdminController::class, 'update_job_ad_status'])->name('update-job-ad-status')->middleware('check.subadmin.permission:ads_edit');
Route::get('view-job-ads/{id}', [App\Http\Controllers\AdminController::class, 'view_job_ads'])->name('view-job-ads')->middleware('check.subadmin.permission:ads_edit');
Route::post('change-job-ad-status', [App\Http\Controllers\AdminController::class, 'change_job_ad_status'])->name('change-job-ad-status')->middleware('check.subadmin.permission:ads_edit');
Route::post('reject-post', [App\Http\Controllers\AdminController::class, 'reject_post'])->name('reject-post')->middleware('check.subadmin.permission:ads_edit');

Route::post('commission/status', [App\Http\Controllers\AdminController::class, 'change_commission'])->name('commission-update-status');


/*------------------  End JOB ADS -------------------*/

/*------------------  Start ADS enquiry -------------------*/

Route::get('ads-enquiry', [App\Http\Controllers\AdminController::class, 'ads_enquiry'])->name('ads-enquiry')->middleware('check.subadmin.permission:ads_edit');

/*------------------  End ADS enquiry -------------------*/

/*------------------  Start enquiry -------------------*/

Route::get('enquiry', [App\Http\Controllers\AdminController::class, 'enquiry'])->name('enquiry')->middleware('check.subadmin.permission:ads_edit');

/*------------------  End  enquiry -------------------*/

/* --------------------  Start RazorPay Setting -----------*/
Route::get('payment-setting',[App\Http\Controllers\AdminController::class,'payment_setting'])->name('payment-setting')->middleware('check.subadmin.permission:setting_edit');
Route::post('update-razorpay-setting',[App\Http\Controllers\AdminController::class,'update_razorpay_setting'])->name('update-razorpay-setting')->middleware('check.subadmin.permission:setting_edit');
Route::post('update-cashfree-setting',[App\Http\Controllers\AdminController::class,'update_cashfree_setting'])->name('update-cashfree-setting')->middleware('check.subadmin.permission:setting_edit');
/* --------------------- End RazorPay Setting ----------------*/

/* --------------------  Start RazorPay Setting -----------*/
Route::get('transaction-history',[App\Http\Controllers\AdminController::class,'transaction_history'])->name('transaction-history');
Route::get('view-transction-history/{id}',[App\Http\Controllers\AdminController::class,'view_transction_history'])->name('view-transction-history');

/* --------------------- End RazorPay Setting ----------------*/


/*------------------  Call Back enquiry -------------------*/

Route::get('call-back-enquiry', [App\Http\Controllers\AdminController::class, 'call_back_enquiry'])->name('call-back-enquiry')->middleware('check.subadmin.permission:help_edit');

/*------------------  End call back enquiry -------------------*/

/*------------------  Manage Subject  -------------------*/

Route::get('subject', [App\Http\Controllers\AdminController::class, 'subject'])->name('subject')->middleware('check.subadmin.permission:help_edit');
Route::get('add-subject', [App\Http\Controllers\AdminController::class, 'add_subject'])->name('add-subject')->middleware('check.subadmin.permission:help_edit');
Route::post('post-add-subject', [App\Http\Controllers\AdminController::class, 'post_add_subject'])->name('post-add-subject')->middleware('check.subadmin.permission:help_edit');
Route::get('delete-subject/{id}', [App\Http\Controllers\AdminController::class, 'delete_subject'])->name('delete-subject')->middleware('check.subadmin.permission:help_edit');
Route::get('edit-subject/{id}', [App\Http\Controllers\AdminController::class, 'edit_subject'])->name('edit-subject')->middleware('check.subadmin.permission:help_edit');
Route::post('post-edit-subject', [App\Http\Controllers\AdminController::class, 'post_edit_subject'])->name('post-edit-subject')->middleware('check.subadmin.permission:help_edit');

/*------------------  End Manage Subject -------------------*/

/*------------------  Raise a Ticket -------------------*/

Route::get('admin-raise-ticket', [App\Http\Controllers\AdminController::class, 'raise_ticket'])->name('admin-raise-ticket')->middleware('check.subadmin.permission:help_edit');
Route::post('/update-ticket-status', [App\Http\Controllers\AdminController::class, 'updateTicketStatus'])->name('updateTicketStatus')->middleware('check.subadmin.permission:help_edit');


/*------------------  End raise a ticket -------------------*/


/*------------------  Start Subscription History -------------------*/

Route::get('subscription-order', [App\Http\Controllers\AdminController::class, 'subscription_order'])->name('subscription-order');
Route::get('subscription-order-payment-status/{id}', [App\Http\Controllers\AdminController::class, 'subscription_order_payment_status'])->name('subscription-order-payment-status');
Route::get('subscription-history', [App\Http\Controllers\AdminController::class, 'subscription_history'])->name('subscription-history');
/*------------------  End Subscription History -------------------*/



Route::get('professional', [App\Http\Controllers\AdminController::class, 'professional'])->name('professional');
Route::get('add-professional', [App\Http\Controllers\AdminController::class, 'add_professional'])->name('add-professional');
Route::post('post-add-professional', [App\Http\Controllers\AdminController::class, 'post_add_professional'])->name('post-add-professional');
Route::get('delete-professional/{id}', [App\Http\Controllers\AdminController::class, 'delete_professional'])->name('delete-professional');
Route::get('view-professional/{id}', [App\Http\Controllers\AdminController::class, 'view_professional'])->name('view-professional');
Route::post('change-status', [App\Http\Controllers\AdminController::class, 'change_status'])->name('change-status');
Route::post('proff-update', [App\Http\Controllers\AdminController::class, 'proff_update'])->name('proff-update');
Route::get('delete-skill/{id}/{userId}', [App\Http\Controllers\AdminController::class, 'delete_skill'])->name('delete-skill');
Route::post('add-skill', [App\Http\Controllers\AdminController::class, 'add_skill'])->name('add-skill');
Route::post('add-more-images', [App\Http\Controllers\AdminController::class, 'add_more_images'])->name('add-more-images');
Route::get('delete-more-images/{id}', [App\Http\Controllers\AdminController::class, 'delete_more_images'])->name('delete-more-images');
Route::post('update-proffessional-status', [App\Http\Controllers\AdminController::class, 'update_proffessional_status'])->name('update-proffessional-status');
Route::get('ExportProfessional', [App\Http\Controllers\AdminController::class, 'ExportProfessional'])->name('ExportProfessional');

});

/*------------------  START ADMIN SETTING -------------------*/

Route::get('/admin/notifications', [NotificationController::class, 'create'])->name('notifications.create');
Route::post('/admin/notifications', [NotificationController::class, 'store'])->name('notifications.store');

Route::get('admin-setting', [App\Http\Controllers\AdminController::class, 'Adminsetting'])->name('admin-setting')->middleware('check.subadmin.permission:setting_edit');
Route::get('admin-setting/sub-admin', [App\Http\Controllers\AdminController::class, 'SubAdmin'])->name('sub-admin')->middleware('check.subadmin.permission:setting_edit');
Route::post('sub-admin/create', [App\Http\Controllers\AdminController::class, 'SubAdminCreate'])->name('sub-admin-create')->middleware('check.subadmin.permission:setting_edit');
Route::get('sub-admin/show/{id}', [App\Http\Controllers\AdminController::class, 'SubAdminEdit'])->name('sub-admin-show')->middleware('check.subadmin.permission:setting_edit');
Route::get('sub-admin/delete/{id}', [App\Http\Controllers\AdminController::class, 'SubAdminDelete'])->name('sub-admin-delete')->middleware('check.subadmin.permission:setting_edit');
Route::post('sub-admin/update', [App\Http\Controllers\AdminController::class, 'SubAdminUpdate'])->name('sub-admin-update')->middleware('check.subadmin.permission:setting_edit');
Route::get('add-admin-settings', [App\Http\Controllers\AdminController::class, 'Addadminsetting'])->name('add-admin-setting')->middleware('check.subadmin.permission:setting_edit');
Route::post('postadminsetting', [App\Http\Controllers\AdminController::class, 'postadminsetting'])->name('postadminsetting')->middleware('check.subadmin.permission:setting_edit');
Route::get('delete-admin-setting/{id}', [App\Http\Controllers\AdminController::class, 'delete_admin_setting'])->name('delete-subject')->middleware('check.subadmin.permission:setting_edit');
Route::get('edit-admin-setting/{id}', [App\Http\Controllers\AdminController::class, 'edit_admin_setting'])->name('edit-subject')->middleware('check.subadmin.permission:setting_edit');
Route::post('post-edit-admin-setting', [App\Http\Controllers\AdminController::class, 'post_edit_admin_setting'])->name('post-edit-subject')->middleware('check.subadmin.permission:setting_edit');

/*---------------  END ADMIN SETTING -------------------*/

Route::post('/send-notification', [App\Http\Controllers\NotificationController::class, 'sendNotification']);
Route::get('/send-all-notification', [App\Http\Controllers\NotificationController::class, 'sendAllNotification']);
Route::get('/send-notification', function () {
    $data['customers'] = Customer::whereNotNull('fcm_token')->get();
    return view('admin.notification.index',$data);
});


/*------------------  START MANAGE COMMISSION SETTING -------------------*/

Route::get('manage-commission-temporary', [App\Http\Controllers\AdminController::class, 'temporaryDeleteCommission'])->name('manage-commission-temporary')->middleware('check.subadmin.permission:setting_edit');
Route::get('manage-commission-setting', [App\Http\Controllers\AdminController::class, 'Managecommissionsetting'])->name('manage-commission-setting')->middleware('check.subadmin.permission:setting_edit');
Route::get('add-manage-commission-setting', [App\Http\Controllers\AdminController::class, 'Addcommission'])->name('add-manage-commission-setting')->middleware('check.subadmin.permission:setting_edit');
Route::post('addpost-manage-commission-setting', [App\Http\Controllers\AdminController::class, 'Addpost_manage_commission_setting'])->name('addpost-manage-commission-setting')->middleware('check.subadmin.permission:setting_edit');
Route::get('delete-admin-setting/{id}', [App\Http\Controllers\AdminController::class, 'delete_subject'])->name('delete-subject')->middleware('check.subadmin.permission:setting_edit');
Route::get('restore-manage-commission-setting/{id}', [App\Http\Controllers\AdminController::class, 'restore_manage_comission_setting'])->name('restore-manage-commission-setting')->middleware('check.subadmin.permission:setting_edit');
Route::get('delete-manage-commission-setting/{id}', [App\Http\Controllers\AdminController::class, 'delete_manage_comission_setting'])->name('delete-manage-commission-setting')->middleware('check.subadmin.permission:setting_edit');
Route::get('permanent-delete-manage-commission-setting/{id}', [App\Http\Controllers\AdminController::class, 'permanent_delete_manage_comission_setting'])->name('permanent-delete-manage-commission-setting')->middleware('check.subadmin.permission:setting_edit');
Route::get('edit-manage-commission-setting/{id}', [App\Http\Controllers\AdminController::class, 'edit_commission'])->name('edit-manage-commission-setting')->middleware('check.subadmin.permission:setting_edit');
Route::post('post-manage-commission-setting', [App\Http\Controllers\AdminController::class, 'post_manage_commission_setting'])->name('post-manage-commission-setting')->middleware('check.subadmin.permission:setting_edit');

/*---------------  END MANAGE COMMISSION SETTING -------------------*/


/*--------------- Start Manage Users  -------------------*/

Route::get('view-subscriptions/{id}', [App\Http\Controllers\AdminController::class, 'ViewSubscriptions'])->name('view-subscriptions')->middleware('check.subadmin.permission:users_edit');
Route::get('view-subscriptions-detail/{id}', [App\Http\Controllers\AdminController::class, 'ViewSubscriptionsdetail'])->name('view-subscriptions-detail')->middleware('check.subadmin.permission:users_edit');
Route::get('view-all-referrals', [App\Http\Controllers\AdminController::class, 'ViewAllReferrals'])->name('view-all-referrals')->middleware('check.subadmin.permission:users_edit');
Route::get('view-all-waiting-subscribers', [App\Http\Controllers\AdminController::class, 'WaitingSubscribersIndex'])->name('waiting-subscribers')->middleware('check.subadmin.permission:users_edit');
Route::get('view-all-user-seed', [App\Http\Controllers\AdminController::class, 'ViewUserSeedInfo'])->name('waiting-user-seed')->middleware('check.subadmin.permission:users_edit');
Route::get('view-all-auto-joining', [App\Http\Controllers\AdminController::class, 'ViewAllAutoJoining'])->name('view-all-auto-joining')->middleware('check.subadmin.permission:users_edit');

Route::get('view-my-referrals/{id}', [App\Http\Controllers\AdminController::class, 'ViewmyReferrals'])->name('view-my-referrals')->middleware('check.subadmin.permission:users_edit');
Route::get('view-auto-joining-members/{id}', [App\Http\Controllers\AdminController::class, 'ViewAutoJoiningMembers'])->name('view-auto-joining-members')->middleware('check.subadmin.permission:users_edit');
Route::get('view-auto-joining-members-by-subscription/{id}', [App\Http\Controllers\AdminController::class, 'ViewAutoJoiningMembersbysubscriptions'])->name('view-auto-joining-members-by-subscription')->middleware('check.subadmin.permission:users_edit');
Route::get('earnings', [App\Http\Controllers\AdminController::class, 'Earnings'])->name('earnings')->middleware('check.subadmin.permission:wallet_payouts_edit');
Route::get('earnings-user/{id}', [App\Http\Controllers\AdminController::class, 'EarningsUser'])->name('earnings-user')->middleware('check.subadmin.permission:wallet_payouts_edit');

Route::get('user-wallet', [App\Http\Controllers\AdminController::class, 'UserWallet'])->name('user-wallet')->middleware('check.subadmin.permission:wallet_payouts_edit');
Route::get('wallet-history/{id}', [App\Http\Controllers\AdminController::class, 'WalletHistory'])->name('wallet-history')->middleware('check.subadmin.permission:wallet_payouts_edit');

Route::get('payouts', [App\Http\Controllers\AdminController::class, 'Payouts'])->name('payouts')->middleware('check.subadmin.permission:wallet_payouts_edit');

Route::get('user-commissions', [App\Http\Controllers\AdminController::class, 'userPayouts'])->name('user-commissions')->middleware('check.subadmin.permission:wallet_payouts_edit');
Route::get('infocard',[App\Http\Controllers\AdminController::class,'indexInfoCard'])->name('infocard.index')->middleware('check.subadmin.permission:content_edit');
Route::get('update-info-card-status/{id}', [App\Http\Controllers\AdminController::class, 'updateinfocardstatus'])->name('update-info-card-status')->middleware('check.subadmin.permission:content_edit');
Route::get('infocard/create',[App\Http\Controllers\AdminController::class,'createInfoCard'])->name('infocard.create')->middleware('check.subadmin.permission:content_edit');
Route::post('infocard/store',[App\Http\Controllers\AdminController::class,'storeInfoCard'])->name('infocard.store')->middleware('check.subadmin.permission:content_edit');
Route::get('infocard/edit/{id}',[App\Http\Controllers\AdminController::class,'editInfoCard'])->name('infocard.edit')->middleware('check.subadmin.permission:content_edit');
Route::post('infocard/update/{id}',[App\Http\Controllers\AdminController::class,'updateInfoCard'])->name('infocard.update')->middleware('check.subadmin.permission:content_edit');
Route::delete('infocard/delete/{id}',[App\Http\Controllers\AdminController::class,'deleteInfoCard'])->name('infocard.delete')->middleware('check.subadmin.permission:content_edit');
Route::get('pool-wallet-history/{id?}', [App\Http\Controllers\AdminController::class, 'poolWalletHistory'])->name('pool-wallet-history')->middleware('check.subadmin.permission:content_edit');
Route::get('pool-wallet-summery', [App\Http\Controllers\AdminController::class, 'poolWalletSummery'])->name('pool-wallet-summery')->middleware('check.subadmin.permission:content_edit');

Route::get('manage-default-notifications', [App\Http\Controllers\AdminController::class, 'defaultNotifications'])->name('manage-default-notifications')->middleware('check.subadmin.permission:content_edit');
Route::get('default-notification-history', [App\Http\Controllers\AdminController::class, 'defaultNotificationsHistory'])->name('default-notification-history')->middleware('check.subadmin.permission:content_edit');
Route::post('update-notification-contents',[App\Http\Controllers\AdminController::class,'updateNotificationContents'])->name('update-notification-contents')->middleware('check.subadmin.permission:content_edit');
Route::get('custom-notification-history', [App\Http\Controllers\AdminController::class, 'customNotifications'])->name('custom-notification-history')->middleware('check.subadmin.permission:content_edit');

/*---------------  END Manage User -------------------*/

/*---------------  END ADMIN PANEL -------------------*/

Route::get('sendbasicemail', [App\Http\Controllers\AdminController::class, 'basic_email'])->name('sendbasicemail');
Route::get('sendhtmlemail', [App\Http\Controllers\AdminController::class, 'html_email'])->name('sendhtmlemail');
Route::get('sendattachmentemail', [App\Http\Controllers\AdminController::class, 'attachment_email'])->name('sendattachmentemail');
Route::get('logout', [App\Http\Controllers\AdminController::class, 'logout'])->name('logout');


Route::get('send-email', [App\Http\Controllers\AdminController::class, 'sendEmail'])->name('logout');
