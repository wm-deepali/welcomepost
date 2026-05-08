<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\LoginAttempt;
use App\Traits\FCMNotifications;
use App\Models\CustomNotificationHistory;
use App\Models\User;
use App\Models\LevelTransaction;
use Illuminate\Support\Facades\Artisan;
use App\Mail\AdminMailForgetPassword;
use App\Models\InfoCard;
use App\Models\CommissionLevel;
use App\Models\SubAdminPermission;
use App\Models\Customer;
use App\Models\BlockUser;
use App\Exports\CommissionExport;
use App\Imports\CommissionImport;
use App\Models\Professional;
use App\Models\AdminProfile;
use App\Models\Categories;
use App\Models\Subcategories;
use App\Models\SubscriptionHistory;
use App\Models\DefaultNotificationHistory;
use App\Models\SubscriptionOrder;
use App\Models\RazorpaySetting;
use App\Models\Countries;
use App\Models\Facing;
use App\Models\States;
use App\Models\Subject;
use Illuminate\Support\Facades\Hash;
use App\Models\City;
use App\Models\Zip;
use App\Models\Location;
use App\Models\Brand;
use App\Models\Vehicletypes;
use App\Models\Fueltype;
use App\Models\Transmission;
use App\Models\Residence;
use App\Models\Furnishing;
use App\Models\Construction;
use App\Models\Job;
use App\Models\Subscription;
use App\Models\Freetrail;
use App\Models\Blog;
use App\Models\Contact;
use App\Models\Faqcategory;
use App\Models\Faq;
use App\Models\About;
use App\Models\Pages;
use App\Models\Formtype;
use App\Models\Jobforms;
use App\Models\Adsenquiry;
use App\Models\CallBack;
use App\Models\RaiseTicket;
use App\Models\Moreimages;
use App\Models\Skill;
use App\Models\Adposting;
use App\Models\Enquiry;
use App\Models\Adminsettings;
use App\Models\managecommission;
use Session;
use Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Post;
use App\Exports\PostExport;
use DB;
use Mail;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use PDF;
use App\Models\Event;
use App\Models\StoreEvent;
use App\Models\PrimeUser;
use App\Models\Customer_child;
use App\Jobs\CustomerChildQ;
use App\Models\Banner;
use App\Models\CashFreeSetting;
use App\Models\CustomerCommission;
use App\Models\FooterSetting;
use App\Models\WalletAmout;
use Carbon\Carbon;

use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use App\Models\DefaultNotification;


class AdminController extends Controller
{
    use FCMNotifications;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.login');
    }
	
	public function admin_profile()
    {
 		$user = auth()->user();
 		$data['info'] = AdminProfile::find($user->id);
 		return view('admin.profile',$data);
    }
	
	public function update_profile_details(Request $request)
    {
        
	
		DB::table('users')->where('id','1')->update(
		array(
		'name'=>$request->name,
		'mobile'=>$request->mobile,
		'email'=>$request->email
		));
		\Session::put('success','Data Updated Successfully.');
		return redirect("admin-profile");
    }
	
	public function update_profile_logo(Request $request)
    {
	
		//$userprofile=new AdminProfile;
		$userprofile=AdminProfile::find('1');
	
	
    	if($request->file('file')){
    	$imageName = time().'.'.$request->file->extension();
    	$request->file->move(public_path('uploads/admin'),$imageName);
    	$userprofile->logo=url('public/uploads/admin').'/'.$imageName;
    	}else{
    	$userprofile->logo=url('public/uploads/admin/dummy.jpeg'); 
    	} 
     
    	$userprofile->save();
    	\Session::put('success','Data Updated Successfully.');
		return redirect("admin-profile");
    }
	
	public function update_profile_pic(Request $request)
    {
	
		//$userprofile=new AdminProfile;
		$userprofile=AdminProfile::find('1');
	
	
	if($request->file('file')){
	$imageName = time().'.'.$request->file->extension();
	$request->file->move(public_path('uploads/admin'),$imageName);
	$userprofile->profile_pic=url('public/uploads/admin').'/'.$imageName;
	}else{
	$userprofile->profile_pic=url('public/uploads/admin/dummy.jpeg'); 
	} 
 
	$userprofile->save();
	\Session::put('success','Data Updated Successfully.');
		return redirect("admin-profile");
    }

	public function bannerIndex(){
		$data['banners'] = Banner::all();
		return view('admin.banner.index',$data);
	}

	public function bannerCreate(){
		return view('admin.banner.create');
	}

	public function bannerStore(Request $request){
		$request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

		$banner = new Banner();

		if ($request->file('image')) {
			$imageName = time().'.'.$request->image->extension();
			$request->image->move(public_path('uploads/admin'), $imageName);
			$imageUrl = 'uploads/admin/'.$imageName;
			$banner->image = $imageUrl;
		}
		
		$banner->title = $request->title;
		$banner->link = $request->link ?? '';
		$banner->description = $request->description;
		$banner->save();
        return redirect()->route('banner.index')->with('success', 'Banner created successfully.');
	}
	
	public function bannerEdit($id){
	    $data['banner'] = Banner::find($id);
		return view('admin.banner.edit', $data);
	}

	public function bannerUpdate(Request $request, $id){
		$request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

		$banner = Banner::find($id);

		if ($request->file('image')) {
			$imageName = time().'.'.$request->image->extension();
			$request->image->move(public_path('uploads/admin'), $imageName);
			$imageUrl = 'uploads/admin/'.$imageName;
			$banner->image = $imageUrl;
		}
		else
		{
		    $imageUrl = $banner->image;
			$banner->image = $imageUrl;
		}
		
		$banner->title = $request->title;
		$banner->link = $request->link ?? '';
		$banner->description = $request->description;
		$banner->save();
        return redirect()->route('banner.index')->with('success', 'Banner updated successfully.');
	}
	
	public function bannerDelete($id){
		$banner = Banner::findOrFail($id);
		$banner->delete();
		return redirect()->route('banner.index')->with('success', 'Banner deleted successfully.');
	}

	public function footerIndex(){
		$data['footer'] = FooterSetting::first();
		return view('admin.footer-setting.index',$data);
	}

	public function footerStore(Request $request)
	{
		// Validate the incoming request data
		$validatedData = $request->validate([
			'title' => 'required|max:255',
			'description' => 'required',
			'url' => 'required|url',
			'button_text' => 'required',
			'youtube_link' => 'nullable|url',
			'facebook_link' => 'nullable|url',
			'linkedin_link' => 'nullable|url',
			'twitter_link' => 'nullable|url',
			'instagram_link' => 'nullable|url',
		]);

		// Retrieve the first entry of the FooterSetting model or create a new one if it doesn't exist
		$footer = FooterSetting::firstOrNew([]);

		// Assign the request data to the footer model instance
		$footer->title = $request->title;
		$footer->description = $request->description;
		$footer->url = $request->url;
		$footer->button_text = $request->button_text;
		$footer->youtube_link = $request->youtube_link;
		$footer->facebook_link = $request->facebook_link;
		$footer->linkedin_link = $request->linkedin_link;
		$footer->twitter_link = $request->twitter_link;
		$footer->instagram_link = $request->instagram_link;

		// Save the FooterSetting model instance to the database
		$footer->save();

		// Redirect back with a success message
		return redirect()->back()->with('success', 'Footer Updated successfully');
	}



	public function check_mail_otp(Request $request)
    {
		$email = $request->email;
		
		$data = array('name'=>"Virat Gandhi");
		
		$rand = mt_rand(1500, 5000);
		
		DB::table('users')->where('id','1')->update(
		array('otp'=>$rand));
		
		
		/*Mail::send(['text'=>'mail'], $data, function($message) {
         $message->to('saifthegame0001@gmail.com', 'OTP')->subject
            ('Laravel Basic Testing Mail');
         $message->from('saif.quantum@gmail.com','saif khan');
      });*/
	  
	  \Session::put('success','OTP sent successfully.');
		return redirect("otp-verification");
	  
	  
	}

	public function change_commission(Request $request)
	{
		// Find the commission
		$commission = CustomerCommission::find($request->commissionId);

		if ($commission) {
			// Update the status to 'approved' or 'pending' based on your requirement
			$newStatus = $request->newStatus;
			$commission->status = $newStatus;

			// Update payment method and reason if provided
			if ($request->has('paymentMethod')) {
				$commission->payment_method = $request->paymentMethod;
			}
			if ($request->has('reason')) {
				$commission->reason = $request->reason;
			}
			
			$commission->transaction_id = $request->transactionId;
			$commission->payment_date = $request->paymentDate;

			// Check if file is present in the request
			if ($request->hasFile('imageFile')) {
				// Store the uploaded file in the storage directory
				$imagePath = $request->file('imageFile')->store('uploads', 'public');

				// Save the file's location in the database
				$commission->image = $imagePath;
			}

			// Save the commission
			$commission->save();
			if($commission->level_commission == "1" && $commission->level_transaction_id !='')
			{
			    $levelTransaction = LevelTransaction::find($commission->level_transaction_id);
			    if($levelTransaction)
			    {
			        $status = $newStatus == 'approved' ? 'Completed' : 'Pending';
			        $levelTransaction->status = $status;
			        $levelTransaction->save();
			    }
			}
            if($newStatus == 'approved' && $commission->total_earned >0)
            {
                $event = DefaultNotification::where('event', 'payout_done')->first();
                if(!empty($event))
                {
                    $title = $event->title;
                    $content = $event->content;
                    $body = str_replace("#amount",$commission->total_earned, $content);
                    $notifyArray=array(
                        'user_id' => $commission->parent_id,
                        'event_name' => $event->event,
                        'title' => $title,
                        'body' => $body,
                    );
                
                    $this->singleUserNotification($notifyArray);
                }
            }
			return response()->json(['success' => true, 'message' => 'Status and additional details updated successfully']);
		} else {
			// Return error response if commission ID is not found
			return response()->json(['success' => false, 'message' => 'Commission not found']);
		}
	}

	
	
	
	public function otp_verification()
    {
		
		$data['info'] = AdminProfile::find('1');
		return view('admin.otp',$data);
    }
	
	
	public function otp_validate(Request $request)
    {
		
		$otp = $request->otp;
		
		$result = DB::table('users')->where('otp',$otp)->exists();
		
		if($result){
			return view('admin.confirmpassword');
		}else{
			\Session::put('success','Invalid Otp');
		return redirect("otp-verification");
		}
		
	}
	
	public function showLinkRequestForm(){
	    return view('admin.forget-password.index');
	}
	
	public function sendResetLinkEmail(Request $request){
	    
        $adminSetting = Adminsettings::first();
        if ($request->email!=$adminSetting->email_id) {
            return redirect()->back()->with('error','Invalid email id...');
        }

        // Generate a unique token
        $token = Str::random(60);
        $userData = User::find(1);
        // Update user's token in the database
        $user = User::where('email', $userData->email)->first();
        $user->token = $token;
        $user->save();
        $mailData = ['token'=>$token];
        $mailContent =  Mail::to($request->email )->send(new AdminMailForgetPassword($mailData));
        return redirect()->back()->with('success','Password Reset Email Sent...');
	}
	
	public function forgetPasswordForm($token){
	    $user = User::where('token',$token)->exists();
	    if(!$user){
	        return redirect()->route('admin.password.request')->with('error','Invalid Token');
	    }
	    return view('admin.forget-password.reset')->with(['token'=>$token]);;
	}
	
	public function submitForgetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);
    
        // Find user by token
        $user = User::where('token', $request->token)->first();
    
        // Check if user exists and token is valid
        if (!$user) {
            return redirect()->back()->with('error', 'Invalid token.');
        }
    
        // Update user's password
        $user->password = Hash::make($request->password);
        $user->token = null; // Clear token after password reset
        $user->is_account_locked = 0;
        $user->save();
    
        return redirect()->route('admin-login')->with('success', 'Password updated successfully.');
    }
    
    public function toggleMaintenance(Request $request)
    {
        $maintenanceMode = $request->input('maintenance_mode');
        $admin = Adminsettings::first();
        if ($maintenanceMode==0) {
            $admin->is_site_maintainance = 1;
        } else {
             $admin->is_site_maintainance = 0;
        }
        $admin->save();

        return redirect()->back();
    }
	
	public function change_password(Request $request)
    {
		
		$password = Hash::make($request->password);
		DB::table('users')->where('id','1')->update(
		array('password'=>$password));
		
		\Session::put('success','Password Changed Successfully');
		return redirect("admin-profile");
		
	}
	
	
	
	
	 public function generatePDF()
    {
        $data = [
            'title' => 'Welcome to ItSolutionStuff.com',
            'date' => date('m/d/Y')
        ];
          
        $pdf = PDF::loadView('myPDF', $data)->setOptions(['defaultFont' => 'sans-serif']);;
    
        return $pdf->download('ReportCard.pdf');
    }
	
	public function basic_email() {
      $data = array('name'=>"Virat Gandhi");
   
      Mail::send(['text'=>'mail'], $data, function($message) {
         $message->to('saifthegame0001@gmail.com', 'Tutorials Point')->subject
            ('Laravel Basic Testing Mail');
         $message->from('saif.quantum@gmail.com','saif khan');
      });
      echo "Basic Email Sent. Check your inbox.";
   }
   public function html_email() {
      $data = array('name'=>"Virat Gandhi");
      Mail::send('mail', $data, function($message) {
         $message->to('saif.quantum@gmail.com', 'Tutorials Point')->subject
            ('Laravel HTML Testing Mail');
         $message->from('xyz@gmail.com','Virat Gandhi');
      });
      echo "HTML Email Sent. Check your inbox.";
   }
   public function attachment_email() {
      $data = array('name'=>"Virat Gandhi");
      Mail::send('mail', $data, function($message) {
         $message->to('abc@gmail.com', 'Tutorials Point')->subject
            ('Laravel Testing Mail with Attachment');
         $message->attach('C:\laravel-master\laravel\public\uploads\image.png');
         $message->attach('C:\laravel-master\laravel\public\uploads\test.txt');
         $message->from('xyz@gmail.com','Virat Gandhi');
      });
      echo "Email Sent with attachment. Check your inbox.";
   }
	
	
	
	public function login_post(Request $request)
    {
        // Validate the request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);
    
        $credentials = $request->only('email', 'password');
        $user = User::where('email', $request->email)->first();
    
        if ($user) {
            if ($user->is_account_locked) {
                \Session::put('error', 'Your account is deactivated due to too many wrong login attempts.');
                return redirect(url('admin-login'));
            }
    
            if (Auth::attempt($credentials)) {
                // Reset invalid attempt counter on successful login
                $user->invalid_attempt = 0;
                $user->save();
    
                session()->put('admin-login-id', $user->id);
                return redirect(url('admin-dashboard'));
            } else {
                // Increment invalid attempt counter
                $user->invalid_attempt += 1;
                $attemptsRemaining = 3 - $user->invalid_attempt;
    
                if ($user->invalid_attempt >= 3) {
                    $user->is_account_locked = true;
                    $user->save();
    
                    \Session::put('error', 'Your account is deactivated due to too many wrong login attempts.');
                } else {
                    $user->save();
                    \Session::put('error', 'Invalid Username and Password. You have ' . $attemptsRemaining . ' attempts remaining.');
                }
    
                return redirect(url('admin-login'));
            }
        } else {
            \Session::put('error', 'User does not exist.');
            return redirect(url('admin-login'));
        }
    }


   
	  public function admin_dashboard()
    {
        $customer = Customer::where('delete_status','0')->count();
		$sumPoolIncome = Customer::where('delete_status','0')->sum('pool_income');
        $totalWalletamount = WalletAmout::AllWallet()->sum('amount');
        $totalCashfreeAmount = WalletAmout::Cashfree()->sum('amount');
       return view('admin.dashboard')->with(['customer'=>$customer,'totalWallet'=>$totalWalletamount,'totalCashfree'=>$totalCashfreeAmount, 'sumPoolIncome'=>$sumPoolIncome]);
    }
    
     public function admin_chat(Request $request)
    {
        $data['user_id']			= 1;
        $data['isAdmin']            = 1;
        $data['chatroom']			= DB::table('chat_room')->where('isAdmin','1')->orderBy('id','desc')->get();
		$request->session()->put('id', 1);
		$data['max_id'] 			= DB::table('chat_room')->where('isAdmin','1')->max('id');
		
       return view('admin.chat.index',$data);
    }
	
	public function professional()
    {
	   $data['professional']=Professional::orderby('id','desc')->get();
       return view('admin.professional.index',$data);
    }
	
	public function ExportProfessional()
  {
    return Excel::download(new PostExport, 'Professional.xlsx');
  }
	
	public function add_professional()
    {
       return view('admin.professional.add');
    }
	
	
	public function post_add_professional(Request $request)
	
	{

	$validator = Validator::make($request->all(), [
	'name'=>'required|max:100|min:0',
	'mobile'=>'required|max:10|min:0|unique:professionals',
	'email' => 'required|email|max:100|min:0|unique:professionals',
	'password' => 'required|max:100|min:0',
	'address' => 'required|max:100|min:0',
	//'file' => 'required|mimes:jpg,jpeg,png,svg|max:2048',
	]);

	if($validator->fails()){
	return redirect()->back()
	->withErrors($validator)
	->withInput();
	}

	if($request->skill==""){
		\Session::put('success','Please select at least one Skill');
		return redirect()->route('student-list');
	}
	
	
	$rand = mt_rand(1500, 5000);
	$professional_id_code = 'PDI-'.$rand;
	
	$length = 50;
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
	$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	$randomString;
	
	
	$userprofile=new Professional;
	
	
	if($request->file('file')){
	$imageName = time().'.'.$request->file->extension();
	$request->file->move(public_path('uploads/professional'),$imageName);
	$userprofile->image=url('public/uploads/professional').'/'.$imageName;
	}else{
	$userprofile->image=url('public/uploads/professional/dummy.jpeg'); 
	} 
 
 
	$userprofile->name=$request->name;
	$userprofile->professional_id=$professional_id_code;
	$userprofile->remember_token=$randomString;
	$userprofile->email=$request->email;
	$userprofile->mobile=$request->mobile;
	$userprofile->password=$request->password;
	$userprofile->address=$request->address;
	$userprofile->status='0';
	$userprofile->save();
	$professional_id = $userprofile->id;



	if($request->skill){
	for($i=0;$i<count($request->skill);$i++){
	$instrument=new Skill;
	$instrument->professional_id=$professional_id;
	$instrument->skill=$request->skill[$i];
	$instrument->save();
	  }
	}

	\Session::put('success','Data Added Successfully.');
	return redirect()->route('professional');

	}
	
	
	function add_more_images(Request $request)
	{
		
		$validator = Validator::make($request->all(), [
	
	//'proffessional_id'=>'required|max:10|min:0|exists:professionals',
	
	]);

	if($validator->fails()){
	return redirect()->back()
	->withErrors($validator)
	->withInput();
	}
		
		$proff_id = $request->proff_id;
		 if($request->file('filenames'))
         {
            foreach($request->file('filenames') as $file)
            {
                 $name = time().rand(1,50).'.'.$file->extension();
                $file->move(public_path('uploads/professional'), $name);  
                $files[] = $name;  
				
				$userprofile_image=url('public/uploads/professional').'/'.$name;
			   
			   DB::table('proff_images')->insert(
			array(
			'proffessional_id'=>$proff_id,
			'status'=>0,
			'image'=>$userprofile_image
			));
            }
			\Session::put('success','Images Uploaded.');
			 return redirect("view-professional/$proff_id");
         }else{
			 \Session::put('success','Please Select Atleast One Image.');
			 return redirect("view-professional/$proff_id");
		 }
		
		
		
	}
	
	function update_proffessional_status(Request $request)
	{
		
		$proffstatus = $request->proffstatus;
		$id = $request->col_id;
		
		
		$userprofile=Moreimages::find($id);
		
		if($userprofile->status==0){
			$userprofile->status=1;
		$userprofile->save();
	    echo '<span id="status_pending<?php echo $orderDetails->id; ?>" class="badge badge-info">approved</span>';		
		exit();
		}
		
		if($userprofile->status==1){
			$userprofile->status=0;
		$userprofile->save();
			echo '<span id="status_approved<?php echo $orderDetails->id; ?>" class="badge badge-warning">pending</span>';
		exit();
		}
		
	
	}
	
	
	function delete_more_images($id)
	{
		
	$result = DB::table('proff_images')->where('id',$id)->get();
	$proff_id = $result[0]->proffessional_id;
	$result[0]->image;
	$string_no =  strpos($result[0]->image,"public");
	$new_image = substr($result[0]->image,$string_no);
	$str = $result[0]->image;
	$storage =  unlink($new_image);
    if($storage){
		
	Moreimages::where('id',$id)->delete();
	\Session::put('success','File deleted successfully');
	return redirect("view-professional/$proff_id");
		
	}else{
		\Session::put('success','Something went wrong');
	return redirect("view-professional/$proff_id");
	}   
	
		
	}
	
	
	
	function proff_update(Request $request)
	{
		
	 $id = $request->proff_id;
	
	//$userprofile=new Professional;
	$userprofile=Professional::find($id);
	$userprofile->name=$request->name;
	$userprofile->email=$request->email;
	$userprofile->mobile=$request->mobile;
	$userprofile->password=$request->password;
	$userprofile->address=$request->address;
	$userprofile->save();
	\Session::put('success','Data Updated Successfully.');
	return redirect("view-professional/$id");
	
	}
	
	
	
	
	public function delete_professional($id)
    {
		Professional::destroy($id);
		\Session::put('success','Data Deleted Successfully.');
	   return redirect()->route('professional');
	}
	
	public function change_status(Request $request)
	{
		$status = $request->status;
		 $id = $request->proff_id;
			
		if($status=='0'){
		$res=Professional::find($id);
		$res->status='1';
		$res->save();
		}else{
		$res=Professional::find($id);
		$res->status='0';
		$res->save();
		}
		
	}
	
	
	public function view_professional($id)
    {
		$data['info'] = Professional::find($id);
		$data['skill'] = Skill::where('professional_id',$id)->get();
		$data['Moreimages']=Moreimages::where('proffessional_id',$id)->orderby('id','desc')->get();
        
	    return view('admin.professional.detail',$data);
	}
	
	public function delete_skill($id,$userId)
    {
		Skill::destroy($id);
		\Session::put('success','Data Deleted Successfully.');
	   return redirect("view-professional/$userId");
	}
	
	public function indexInfoCard(){
	    $data['infocards'] = InfoCard::all();
	     return view('admin.infocard.index',$data);
	}
	
	public function createInfoCard(){
	    return view('admin.infocard.create');
	}
	
	public function editInfoCard($id){
	    $data['infocard'] = InfoCard::find($id);
	    return view('admin.infocard.edit', $data);
	}
	
	public function storeInfoCard(Request $request){
	    $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'url' => 'nullable|string|max:255',
        ]);

        // Store the image file
        $infoCard = new InfoCard();
        if ($request->file('icon')) {
			$imageName = time().'.'.$request->icon->extension();
			$request->icon->move(public_path('uploads/admin'), $imageName);
			$imageUrl = 'uploads/admin/'.$imageName;
			$infoCard->icon = $imageUrl;
		}
        $infoCard->title = $validatedData['title'];
        $infoCard->description = $validatedData['description'];
        $infoCard->url = $validatedData['url'];
        $infoCard->save();

        // Redirect back with success message
        return redirect()->route('infocard.index')->with('success', 'InfoCard created successfully.');
        
	}
	
	public function updateInfoCard(Request $request, $id){
	    $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'url' => 'nullable|string|max:255',
        ]);

        // Store the image file
        $infoCard = InfoCard::find($id);
        if ($request->file('icon')) {
			$imageName = time().'.'.$request->icon->extension();
			$request->icon->move(public_path('uploads/admin'), $imageName);
			$imageUrl = 'uploads/admin/'.$imageName;
			$infoCard->icon = $imageUrl;
		}
		else
		{
		    $imageUrl = $infoCard->icon;
		    $infoCard->icon = $imageUrl;
		}
        $infoCard->title = $validatedData['title'];
        $infoCard->description = $validatedData['description'];
        $infoCard->url = $validatedData['url'];
        $infoCard->save();

        // Redirect back with success message
        return redirect()->route('infocard.index')->with('success', 'InfoCard updated successfully.');
        
	}
	
	public function deleteInfoCard($id){
	    $infocard = InfoCard::findOrFail($id);
	    $infocard->delete();
	    return redirect()->back()->with('success', 'InfoCard deleted successfully.');
	}
	
	
	
	public function add_skill(Request $request)
	
	{
		
		$id = $request->proff_id;
     if($request->skill==""){
		\Session::put('success','Please select at least one instrument');
		return redirect("view-professional/$id");
	}
    
	if($request->skill){
	for($i=0;$i<count($request->skill);$i++){
	$instrument=new Skill;
	$instrument->professional_id=$request->proff_id;
	$instrument->skill=$request->skill[$i];
	$instrument->save();
	  }
	}

	\Session::put('success','Data Added Successfully.');
	return redirect("view-professional/$id");

	}
	
	
	/* ---------------- Start User Management -------------------*/
	
	public function user()
    {
	   $data['user']=Customer::with('loginAttempt')->where('delete_status','0')->orderby('id','desc')->get();
       return view('admin.user.index',$data);
    }
	public function deleteduser()
    {
	   $data['user']=Customer::where('delete_status','1')->withTrashed()->orderby('id','desc')->get();
       return view('admin.user.deleteduser',$data);
    }
	
	public function add_user()
    {
       return view('admin.user.add');
    }


	public function add_users()
    {

       return view('admin.user.add-users');
    }
	
	public function post_add_user(Request $request)
	
	{

	$validator = Validator::make($request->all(), [
	
	'name'=>'required|max:50|min:0',
	]);

	if($validator->fails()){
	return redirect()->back()
	->withErrors($validator)
	->withInput();
	}

	$userprofile=new Customer;
	$userprofile->name=$request->name;
	$userprofile->email=$request->email;
	$userprofile->email=$request->password;
	$userprofile->email=$request->email;
	$userprofile->delete_status='0';
	$userprofile->status='0';
	$userprofile->save();
	
	\Session::put('success','Data Added Successfully.');
	return redirect()->route('user');

	}
	
	public function unlockUserAccount($id){
	    $loginAttempt = LoginAttempt::findOrFail($id);
	    $loginAttempt->is_account_locked = 0;
	    $loginAttempt->save();
	    \Session::put('success','User Unlocked Successfully.');
	    return redirect()->back();
	}
	
	
	public function deletep_user($id)
    {
        
        // Retrieve the user profile
        $userprofile = Customer::withTrashed()->findOrFail($id);
    
        if(!empty($userprofile))
        {
            // Remove parent_id and reserve_expiry_at from the customer table
            Customer::where('parent_id', $userprofile->id)
            ->update(['parent_id' => null, 'reserve_expiry_at' => null]);
           
            
             // Delete rows from the customer_child table where parent_id matches
           //$cchild = Customer_child::where('user_id', $userprofile->id)->get();
           
            if(isset($userprofile->customerchild)&&count($userprofile->customerchild)>0){
                $userprofile->customerchild->each->delete();
            }
           
            
            $subscriptionExists = SubscriptionHistory::where('user_id',$userprofile->id)->exists();
            if($subscriptionExists){
                SubscriptionHistory::where('user_id',$userprofile->id)->delete();
            }
            
            // Force delete the user profile
            $userprofile->forceDelete();
            $adsposting = Adposting::where('user_id',$id)->get();
            
            if(isset($adsposting)&&count($adsposting)>0){
                Adposting::where('user_id',$id)->delete();
            }
            
            $customerChild = Customer_child::where('user_id',$id)->get();
            if(isset($customerChild)&&count($customerChild)>0){
                $customerChild->delete();
            }
            
            $user_commission = CustomerCommission::where('parent_id',$userprofile->id)->get();
            if(isset($user_commission)&&count($user_commission)>0){
                $user_commission->delete();
            }
        }
        
        
        // Set a success message and redirect
        \Session::put('success', 'Data Permanently Deleted Successfully.');
        return redirect()->route('user');
    }

	public function restore_user($id)
    {
		
    	$userprofile=Customer::withTrashed()->findOrFail($id);
    
    	$userprofile->delete_status='0';
    
    	$userprofile->save();
    	$userprofile->restore();
    	\Session::put('success','Data Restore Successfully.');
    	return redirect()->route('user');
    		
    }
    
    public function delete_user($id)
        {
    	$userprofile=Customer::find($id);
        $userprofile->deleted_by = Auth::id();
    	$userprofile->delete_status='1';
    	$userprofile->save();
    	$userprofile->delete();
    	
    	\Session::put('success','Data Deletes Successfully.');
    	return redirect()->route('user');
		
	}
	
	
	
	public function view_user($id)
    {
		$data['info'] = Customer::find($id);
	    return view('admin.user.view',$data);
	}

	public function edit_user($id)
    {
		$data['info'] = Customer::find($id);
	    return view('admin.user.detail',$data);
	}
	
	function post_edit_user(Request $request)
	{
		// dd($request->all());

	 $id = $request->id;
	 $userprofile = Customer::find($id);
	 $userprofile->name = $request->name;
	 $userprofile->email = $request->email;
	 if (!empty($request->password)) {
        $password = bcrypt($request->password);

    } else {
        $password = $userprofile->password;
    }
	 $userprofile->password = $password;
	 $userprofile->mobile = $request->mobile;
	 $userprofile->user_type = $request->user_type;
	 $userprofile->gender = $request->gender;
	 $userprofile->dob = $request->dob;
	 $userprofile->address = $request->address;
	 $userprofile->country = $request->country;
	 $userprofile->state = $request->state;
	 $userprofile->city = $request->city;
	 $userprofile->pin = $request->pin;
	 $userprofile->introduction 		= $request->introduction;
     $userprofile->website 			= $request->website;
     $userprofile->youtube 			= $request->youtube;
     $userprofile->facebook 			= $request->facebook;
     $userprofile->twitter 			= $request->twitter;
     $userprofile->whatsapp 			= $request->whatsapp;
	 $userprofile->adhar_number = $request->adhar_number;
	 $userprofile->pancard_num = $request->pancard_num;
	 $userprofile->bank_name = $request->bank_name;
	 $userprofile->bank_branch = $request->bank_branch;
	 $userprofile->account_name = $request->account_name;
	 $userprofile->account_number = $request->account_number;
	 $userprofile->account_ifsc = $request->account_ifsc;
	 $userprofile->upi_id = $request->upi_id;


     if (request()->hasFile('image')) {
    	$image = $request->file('image');
    	$images = "image".time().'.'.$image->getClientOriginalExtension();
    	$destinationPath = public_path('/admin/images');
    	$image->move($destinationPath, $images);
    	$userprofile->image = url('public/admin/images/'.$images);
    }

	if (request()->hasFile('aadharfront')) {
		$aadharfront = $request->file('aadharfront');
		$aadharfronts = "ad".time().'.'.$aadharfront->getClientOriginalExtension();
		$destinationPath1 = public_path('/admin/images');
		$aadharfront->move($destinationPath1, $aadharfronts);
		$userprofile->aadharfronts = $aadharfronts;
		
	}



	if (request()->hasFile('aadharback')) {
		$aadharback = $request->file('aadharback');
		$aadharbacks = "adb".time().'.'.$aadharback->getClientOriginalExtension();
		$destinationPath2 = public_path('/admin/images');
		$aadharback->move($destinationPath2, $aadharbacks);
		$userprofile->aadharback = $aadharbacks;
	}


	if (request()->hasFile('pancard')) {
		$pancard = $request->file('pancard');
		$pancards = "pan".time().'.'.$pancard->getClientOriginalExtension();
		$destinationPath3 = public_path('/admin/images');
		$pancard->move($destinationPath3, $pancards);
		$userprofile->pancard = $pancards;
	}
	if (request()->hasFile('cheque')) {
		$cheque = $request->file('cheque');
		$cheques = "pan".time().'.'.$cheque->getClientOriginalExtension();
		$destinationPath4 = public_path('/admin/images');
		$cheque->move($destinationPath4, $cheques);
		$userprofile->cheque = $cheques;
	}

	
	$userprofile->save();
	\Session::put('success','Data Updated Successfully.');
	return redirect("edit-user/$id");
	
	}
	
	public function update_user($id)
	{
	    $user = Customer::find($id);
	    
	    if($user->status == 0)
	    {
	        $user->status = 1;
			$user->is_email_verified = 1;
	        $user->save();
	    }else{
	        $user->status = 0;
			
	        $user->save();
	    }

	    \Session::put('success','Data Updated Successfully.');
	    return redirect()->route('user');
	}
	
	/* ---------------- End User Management -------------------*/
	
	/* ----------------Start Category Management -------------------*/
	
	
	public function categories()
    {
	   $data['categories']=Categories::where('delete_status','0')->orderby('id','desc')->get();
       return view('admin.categories.index',$data);
    }
	
	
	
	public function add_categories()
    {
	   $data['Formtype']=Formtype::where('delete_status','0')->orderby('id','desc')->get();
       return view('admin.categories.add',$data);
    }
	
	
	public function post_add_categories(Request $request)
	
	{
		
	$validator = Validator::make($request->all(), [
	'name'=>'required|max:50|min:0',
	'formtype'=>'required|max:50|min:0',
	'meta_title'=>'required|max:100|min:0',
	'meta_keyword'=>'required|max:100|min:0',
	'meta_description'=>'required|max:100|min:0',
	//'file' => 'required|mimes:jpg,jpeg,png,svg|max:2048',
	]);

	if($validator->fails()){
	return redirect()->back()
	->withErrors($validator)
	->withInput();
	}

	
	$userprofile=new Categories;
	
	
	if($request->file('file')){
	$imageName = time().'.'.$request->file->extension();
	$request->file->move(public_path('uploads/categories'),$imageName);
	$userprofile->image=url('public/uploads/categories').'/'.$imageName;
	}else{
	$userprofile->image=url('public/uploads/categories/dummy.jpeg'); 
	}

	
	if($request->file('filetwo')){
	$imageNametwo = time().'.'.$request->filetwo->extension();
	$request->filetwo->move(public_path('uploads/categories'),$imageNametwo);
	$userprofile->icon=url('public/uploads/categories').'/'.$imageNametwo;
	}else{
	$userprofile->icon=url('public/uploads/categories/dummy.jpeg'); 
	} 	
	
	
	$name = $request->name;
    $string = preg_replace('/\s+/','-', $name);	
	//echo $url =  preg_replace('/[^A-Za-z0-9\-]/', '', $name);
	//echo $string = preg_replace('/\s+/','-', $url);
	//echo $updates_url =  strtolower($url);
	//$url =  preg_replace('/[^A-Za-z0-9\-]/', '', $name);
    $updates_url =  strtolower($string);
	
	$result_url = DB::table('categories')->where('url',$updates_url)->exists();
	if($result_url){
		\Session::put('success','URL already exists.');
	return redirect()->route('add-categories');
	}
     
	$userprofile->name=$request->name;
	$userprofile->formtype=$request->formtype;
	$userprofile->url=$updates_url;
	$userprofile->meta_title=$request->meta_title;
	$userprofile->meta_keyword=$request->meta_keyword;
	$userprofile->meta_description=$request->meta_description;
	$userprofile->delete_status='0';
	$userprofile->no_of_ads='0';
	$userprofile->status='0';
	$userprofile->premium='0';
	$userprofile->top='0';
	$userprofile->trending='0';
	$userprofile->save();
	
	$freeTrial=new Freetrail;
	$freeTrial->category_id=$userprofile->id;
	$freeTrial->no_of_ads=10;
	$freeTrial->ads_validity=30;
	$freeTrial->delete_status='0';
	$freeTrial->status='0';
	$freeTrial->save();
	
	\Session::put('success','Data Added Successfully.');
	return redirect()->route('categories');

	}
	
	
	public function view_categories($id)
    {
		$data['info'] = Categories::find($id);
		$data['Formtype']=Formtype::where('delete_status','0')->orderby('id','desc')->get();
	    return view('admin.categories.detail',$data);
	}
	
	public function view_form($formtype)
    {
		
		if($formtype == 1){
		return view('admin.categories.jobformtype');	
		}elseif($formtype == 2){
		 return view('admin.categories.mobileformtype');	
		}elseif($formtype == 3){
		 return view('admin.categories.vehicleformtype');	
		}elseif($formtype == 4){
		 return view('admin.categories.propertyformtype');	
		}else{
		 return view('admin.categories.commonformtype');		
		}
	    
	}
	
	
	function post_edit_categories(Request $request)
	{
		
		$validator = Validator::make($request->all(), [
	'name'=>'required|max:50|min:0',
	'formtype'=>'required|max:50|min:0',
	'meta_title'=>'required|max:100|min:0',
	'meta_keyword'=>'required|max:100|min:0',
	'meta_description'=>'required|max:100|min:0',
	//'file' => 'required|mimes:jpg,jpeg,png,svg|max:2048',
	]);

	if($validator->fails()){
	return redirect()->back()
	->withErrors($validator)
	->withInput();
	}
		
	 $id = $request->id;
	 $old_image = $request->old_image;
	 $old_image2 = $request->old_image2;
	 
	 $userprofile=Categories::find($id);
	 
	if($request->file('file')){
	$imageName = time().'.'.$request->file->extension();
	$request->file->move(public_path('uploads/categories'),$imageName);
	$userprofile->image=url('public/uploads/categories').'/'.$imageName;
	}else{
	$userprofile->image=$old_image;
	}

    if($request->file('filetwo')){
	$imageNametwo = time().'.'.$request->filetwo->extension();
	$request->filetwo->move(public_path('uploads/categories'),$imageNametwo);
	$userprofile->icon=url('public/uploads/categories').'/'.$imageNametwo;
	}else{
	$userprofile->icon=$old_image2;
	}	
	
	$userprofile->name=$request->name;
	$userprofile->formtype=$request->formtype;
	$userprofile->meta_title=$request->meta_title;
	$userprofile->meta_keyword=$request->meta_keyword;
	$userprofile->meta_description=$request->meta_description;
	$userprofile->save();
	\Session::put('success','Data Updated Successfully.');
	return redirect("view-categories/$id");
	
	}
	
	
	public function delete_categories($id)
    {
		
	$userprofile=Categories::find($id);
    $adsposting = Adposting::where('category_id',$id)->get();
    if(isset($adsposting)&&count($adsposting)>0){
        Adposting::where('category_id',$id)->delete();
    }
        
	$userprofile->delete_status='1';

	$userprofile->save();
	\Session::put('success','Data Deletes Successfully.');
	return redirect()->route('categories');
		
	}
	
	public function update_status($id,$status)
    {
		
		
	$userprofile=Categories::find($id);
     
	if($status == '0'){
	   $userprofile->status='1';	
	}else{
	   $userprofile->status='0';
	}
	

	$userprofile->save();
	\Session::put('success','Status Updated Successfully.');
	return redirect()->route('categories');
		
	}
	
	
	public function update_premium($id,$status)
    {
		
		
	$userprofile=Categories::find($id);
     
	if($status == '0'){
	   $userprofile->premium='1';	
	}else{
	   $userprofile->premium='0';
	}
	
	$userprofile->save();
	\Session::put('success','Status Updated Successfully.');
	return redirect()->route('categories');
		
	}
	
	public function update_top($id,$status)
    {
		
		
	$userprofile=Categories::find($id);
     
	if($status == '0'){
	   $userprofile->top='1';	
	}else{
	   $userprofile->top='0';
	}
	

	$userprofile->save();
	\Session::put('success','Status Updated Successfully.');
	return redirect()->route('categories');
		
	}
	
	public function update_trending($id,$status)
    {
		
		
	$userprofile=Categories::find($id);
     
	if($status == '0'){
	   $userprofile->trending='1';	
	}else{
	   $userprofile->trending='0';
	}
	

	$userprofile->save();
	\Session::put('success','Status Updated Successfully.');
	return redirect()->route('categories');
		
	}
	
	
	
	/* ---------------- End Category Management -------------------*/
	
	/* ---------------- Start Sub Category Management -------------------*/

	
	public function sub_categories()
    {
	   $data['subcategories']=Subcategories::where('delete_status','0')->orderby('id','desc')->get();
       return view('admin.sub-categories.index',$data);
    }
	
	
	public function add_subcategories()
    {
	   $data['categories']=Categories::where('delete_status','0')->orderby('id','desc')->get();
       return view('admin.sub-categories.add',$data);
    }
	
	
	public function post_add_subcategories(Request $request)
	
	{

	$validator = Validator::make($request->all(), [
	'name'=>'required|max:50|min:0',
	'meta_title'=>'required|max:100|min:0',
	'meta_keyword'=>'required|max:100|min:0',
	'meta_description'=>'required|max:100|min:0',
	//'file' => 'required|mimes:jpg,jpeg,png,svg|max:2048',
	]);

	if($validator->fails()){
	return redirect()->back()
	->withErrors($validator)
	->withInput();
	}

	
	$userprofile=new Subcategories;
	
	
	if($request->file('file')){
	$imageName = time().'.'.$request->file->extension();
	$request->file->move(public_path('uploads/subcategories'),$imageName);
	$userprofile->icon=url('public/uploads/subcategories').'/'.$imageName;
	}else{
	$userprofile->icon=url('public/uploads/subcategories/dummy.jpeg'); 
	} 
	
	$name = $request->name;
    $string = preg_replace('/\s+/','-', $name);	
	//echo $url =  preg_replace('/[^A-Za-z0-9\-]/', '', $name);
	//echo $string = preg_replace('/\s+/','-', $url);
	//echo $updates_url =  strtolower($url);
	//$url =  preg_replace('/[^A-Za-z0-9\-]/', '', $name);
    $updates_url =  strtolower($string);
	
	$result_url = DB::table('subcategories')->where('url',$updates_url)->exists();
	if($result_url){
		\Session::put('success','URL already exists.');
	return redirect()->route('add-subcategories');
	}
	
 
	$userprofile->category_id=$request->category;
	$userprofile->name=$request->name;
	$userprofile->url=$updates_url;
	$userprofile->slug=$updates_url;
	$userprofile->meta_title=$request->meta_title;
	$userprofile->meta_keyword=$request->meta_keyword;
	$userprofile->meta_description=$request->meta_description;
	$userprofile->delete_status='0';
	$userprofile->save();
	
	\Session::put('success','Data Added Successfully.');
	return redirect()->route('sub-categories');

	}
	
	
	public function delete_subcategories($id)
    {
		
	$userprofile=Subcategories::find($id);
    $adsposting = Adposting::where('sub_category_id',$id)->get();
    if(isset($adsposting)&&count($adsposting)>0){
        Adposting::where('sub_category_id',$id)->delete();
    }
	$userprofile->delete_status='1';

	$userprofile->save();
	\Session::put('success','Data Deletes Successfully.');
	return redirect()->route('sub-categories');
		
	}
	
	public function edit_subcategories($id)
    {
		$data['info'] = Subcategories::find($id);
	    return view('admin.sub-categories.detail',$data);
	}
	
	
	
	function post_edit_subcategories(Request $request)
	{
		$validator = Validator::make($request->all(), [
	'name'=>'required|max:50|min:0',
	'meta_title'=>'required|max:100|min:0',
	'meta_keyword'=>'required|max:100|min:0',
	'meta_description'=>'required|max:100|min:0',
	//'file' => 'required|mimes:jpg,jpeg,png,svg|max:2048',
	]);

	if($validator->fails()){
	return redirect()->back()
	->withErrors($validator)
	->withInput();
	}
		
	$id = $request->id;
	$old_image = $request->file;
	 
	$userprofile=Subcategories::find($id);
	$oldCategoryId = $userprofile->category_id;
    $newCategoryId = $request->category;
    
    // Check if there are any ads with the specified category ID
    if (Adposting::where('category_id', $oldCategoryId)->exists()) {
        // Update the category_id if ads with the specified category ID exist
        Adposting::where('category_id', $oldCategoryId)->update(['category_id' => $newCategoryId]);
    }
	if($request->file('file')){
    	$imageName = time().'.'.$request->file->extension();
    	$request->file->move(public_path('uploads/subcategories'),$imageName);
    	$userprofile->icon=url('public/uploads/subcategories').'/'.$imageName;
	}
	
	//$userprofile=new Professional;
	$userprofile->category_id = $request->category;
	$userprofile->name=$request->name;
	$userprofile->meta_title=$request->meta_title;
	$userprofile->meta_keyword=$request->meta_keyword;
	$userprofile->meta_description=$request->meta_description;
	$userprofile->save();
	\Session::put('success','Data Updated Successfully.');
	return redirect("edit-subcategories/$id");
	
	}
	
	
	public function view_subcategories($id)
    {
		$data['info'] = Subcategories::find($id);
	    return view('admin.sub-categories.view',$data);
	}
	
	
	
	
	/* ---------------- End Sub Category Management -------------------*/
	
	
	/* ---------------- Start Country Management -------------------*/
	
	public function countries()
    {
	   $data['countries']=Countries::where('delete_status','0')->orderby('id','desc')->get();
       return view('admin.countries.index',$data);
    }
	
	
	public function add_countries()
    {
       return view('admin.countries.add');
    }
	
	public function post_add_countries(Request $request)
	
	{

	$validator = Validator::make($request->all(), [
	'name'=>'required|max:50|min:0',
	]);

	if($validator->fails()){
	return redirect()->back()
	->withErrors($validator)
	->withInput();
	}

	$userprofile=new Countries;
	$userprofile->name=$request->name;
	$userprofile->delete_status='0';
	$userprofile->status='0';
	$userprofile->save();
	
	\Session::put('success','Data Added Successfully.');
	return redirect()->route('countries');

	}
	
	
	public function delete_countries($id)
    {
		
	$userprofile=Countries::find($id);

	$userprofile->delete_status='1';

	$userprofile->save();
	\Session::put('success','Data Deletes Successfully.');
	return redirect()->route('countries');
		
	}
	
	public function edit_countries($id)
    {
		$data['info'] = Countries::find($id);
	    return view('admin.countries.detail',$data);
	}
	
	
	
	function post_edit_countries(Request $request)
	{
		$validator = Validator::make($request->all(), [
	'name'=>'required|max:50|min:0',
	]);

	if($validator->fails()){
	return redirect()->back()
	->withErrors($validator)
	->withInput();
	}
		
	 $id = $request->id;
	 $userprofile=Countries::find($id);
	$userprofile->name=$request->name;
	$userprofile->save();
	\Session::put('success','Data Updated Successfully.');
	return redirect("edit-countries/$id");
	
	}
	
	
	/* ---------------- End Country Management -------------------*/
	
	
	/* ---------------- Start State Management -------------------*/
	
	public function states()
    {
	   $data['states']=States::where('delete_status','0')->orderby('id','desc')->get();
       return view('admin.states.index',$data);
    }
	
	
	public function add_states()
    {
		$data['country']=Countries::where('delete_status','0')->orderby('id','desc')->get();
       return view('admin.states.add',$data);
    }
	
	public function post_add_states(Request $request)
	
	{

	$validator = Validator::make($request->all(), [
	'name'=>'required|max:50|min:0',
	]);

	if($validator->fails()){
	return redirect()->back()
	->withErrors($validator)
	->withInput();
	}
	$userprofile=new States;
	$userprofile->country_id=$request->country;
	$userprofile->name=$request->name;
	$userprofile->delete_status='0';
	$userprofile->status='0';
	$userprofile->save();
	
	\Session::put('success','Data Added Successfully.');
	return redirect()->route('states');

	}
	
	
	public function delete_states($id)
    {
		
	$userprofile=States::find($id);

	$userprofile->delete_status='1';

	$userprofile->save();
	\Session::put('success','Data Deletes Successfully.');
	return redirect()->route('states');
		
	}
	
	public function edit_states($id)
    {
		$data['info'] = States::find($id);
	    return view('admin.states.detail',$data);
	}
	
	
	
	function post_edit_states(Request $request)
	{
		
		$validator = Validator::make($request->all(), [
	'name'=>'required|max:50|min:0',
	]);

	if($validator->fails()){
	return redirect()->back()
	->withErrors($validator)
	->withInput();
	}
		
	 $id = $request->id;
	 $userprofile=States::find($id);
	$userprofile->name=$request->name;
	$userprofile->save();
	\Session::put('success','Data Updated Successfully.');
	return redirect("edit-states/$id");
	
	}
	
	
	/* ---------------- End State Management -------------------*/
	
	
	/* ---------------- Start City Management -------------------*/
	
	public function city()
    {
	   $data['city']=City::where('delete_status','0')->orderby('id','desc')->get();
       return view('admin.city.index',$data);
    }
	
	
	public function add_city()
    {
		$data['country']=Countries::where('delete_status','0')->orderby('id','desc')->get();
		$data['state']=States::where('delete_status','0')->orderby('id','desc')->get();
       return view('admin.city.add',$data);
    }
	
	public function post_add_city(Request $request)
	
	{

	$validator = Validator::make($request->all(), [
	'name'=>'required|max:50|min:0',
	]);

	if($validator->fails()){
	return redirect()->back()
	->withErrors($validator)
	->withInput();
	}

	$userprofile=new City;
	$userprofile->country_id=$request->country;
	$userprofile->state_id=$request->state;
	$userprofile->name=$request->name;
	$userprofile->delete_status='0';
	$userprofile->status='0';
	$userprofile->save();
	
	\Session::put('success','Data Added Successfully.');
	return redirect()->route('city');

	}
	
	
	public function delete_city($id)
    {
		
	$userprofile=City::find($id);

	$userprofile->delete_status='1';

	$userprofile->save();
	\Session::put('success','Data Deletes Successfully.');
	return redirect()->route('city');
		
	}
	
	public function edit_city($id)
    {
		$data['info'] = City::find($id);
	    return view('admin.city.detail',$data);
	}
	
	
	
	function post_edit_city(Request $request)
	{
		
		$validator = Validator::make($request->all(), [
	'name'=>'required|max:50|min:0',
	]);

	if($validator->fails()){
	return redirect()->back()
	->withErrors($validator)
	->withInput();
	}
		
	 $id = $request->id;
	 $userprofile=City::find($id);
	$userprofile->name=$request->name;
	$userprofile->save();
	\Session::put('success','Data Updated Successfully.');
	return redirect("edit-city/$id");
	
	}
	
	
	/* ---------------- End City Management -------------------*/
	
	
	/* ---------------- Start Zip Management -------------------*/
	
	public function zip()
    {
	   $data['zip']=Zip::where('delete_status','0')->orderby('id','desc')->get();
       return view('admin.zip.index',$data);
    }
	
	
	public function add_zip()
    {
		$data['country']=Countries::where('delete_status','0')->orderby('id','desc')->get();
		$data['state']=States::where('delete_status','0')->orderby('id','desc')->get();
		$data['city']=City::where('delete_status','0')->orderby('id','desc')->get();
       return view('admin.zip.add',$data);
    }
	
	public function post_add_zip(Request $request)
	
	{

	$validator = Validator::make($request->all(), [
	]);

	if($validator->fails()){
	return redirect()->back()
	->withErrors($validator)
	->withInput();
	}

	$userprofile=new Zip;
	$userprofile->country_id=$request->country;
	$userprofile->state_id=$request->state;
	$userprofile->city_id=$request->city;
	$userprofile->code=$request->zip;
	$userprofile->delete_status='0';
	$userprofile->status='0';
	$userprofile->save();
	
	\Session::put('success','Data Added Successfully.');
	return redirect()->route('zip');

	}
	
	
	public function delete_zip($id)
    {
		
	$userprofile=Zip::find($id);

	$userprofile->delete_status='1';

	$userprofile->save();
	\Session::put('success','Data Deletes Successfully.');
	return redirect()->route('zip');
		
	}
	
	public function edit_zip($id)
    {
		$data['info'] = Zip::find($id);
	    return view('admin.zip.detail',$data);
	}
	
	
	
	function post_edit_zip(Request $request)
	{
		
	 $id = $request->id;
	 $userprofile=Zip::find($id);
	$userprofile->code=$request->zip;
	$userprofile->save();
	\Session::put('success','Data Updated Successfully.');
	return redirect("edit-zip/$id");
	
	}
	
	
	/* ---------------- End Zip Management -------------------*/
	
	
	
	/* ---------------- Start Location Management -------------------*/
	
	public function location()
    {
	   $data['location']=Location::where('delete_status','0')->orderby('id','desc')->get();
       return view('admin.location.index',$data);
    }
	
	
	public function add_location()
    {
		$data['country']=Countries::where('delete_status','0')->orderby('id','desc')->get();
		$data['state']=States::where('delete_status','0')->orderby('id','desc')->get();
		$data['city']=City::where('delete_status','0')->orderby('id','desc')->get();
       return view('admin.location.add',$data);
    }
	
	public function post_add_location(Request $request)
	
	{

	$validator = Validator::make($request->all(), [
	]);

	if($validator->fails()){
	return redirect()->back()
	->withErrors($validator)
	->withInput();
	}

	$userprofile=new Location;
	$userprofile->country_id=$request->country;
	$userprofile->state_id=$request->state;
	$userprofile->city_id=$request->city;
	$userprofile->location=$request->location;
	$userprofile->delete_status='0';
	$userprofile->status='0';
	$userprofile->save();
	
	\Session::put('success','Data Added Successfully.');
	return redirect()->route('location');

	}
	
	
	public function delete_location($id)
    {
		
	$userprofile=Location::find($id);

	$userprofile->delete_status='1';

	$userprofile->save();
	\Session::put('success','Data Deletes Successfully.');
	return redirect()->route('location');
		
	}
	
	public function edit_location($id)
    {
		$data['info'] = Location::find($id);
	    return view('admin.location.detail',$data);
	}
	
	
	
	function post_edit_location(Request $request)
	{
		
	 $id = $request->id;
	 $userprofile=Location::find($id);
	$userprofile->location=$request->location;
	$userprofile->save();
	\Session::put('success','Data Updated Successfully.');
	return redirect("edit-location/$id");
	
	}
	
	
	/* ---------------- End Location Management -------------------*/
	
	
	/* ---------------- Start Brand Management -------------------*/
	
	public function brand()
    {
	   $data['brand']=Brand::where('delete_status','0')->orderby('id','desc')->get();
       return view('admin.brand.index',$data);
    }
	
	
	public function add_brand()
    {
        $data['categories']=Categories::where('delete_status','0')->orderby('id','desc')->get();
       return view('admin.brand.add',$data);
    }
	
	public function post_add_brand(Request $request)
	
	{

	$validator = Validator::make($request->all(), [
	
	'name'=>'required|max:50|min:0',
	]);

	if($validator->fails()){
	return redirect()->back()
	->withErrors($validator)
	->withInput();
	}

	$userprofile=new Brand;
	$userprofile->type=$request->type;
	$userprofile->name=$request->name;
	$userprofile->delete_status='0';
	$userprofile->status='0';
	$userprofile->save();
	
	\Session::put('success','Data Added Successfully.');
	return redirect()->route('brand');

	}
	
	
	public function delete_brand($id)
    {
		
	$userprofile=Brand::find($id);

	$userprofile->delete_status='1';

	$userprofile->save();
	\Session::put('success','Data Deletes Successfully.');
	return redirect()->route('brand');
		
	}
	
	public function edit_brand($id)
    {
		$data['info'] = Brand::find($id);
	    return view('admin.brand.detail',$data);
	}
	
	
	
	function post_edit_brand(Request $request)
	{
		
		$validator = Validator::make($request->all(), [
	
	'name'=>'required|max:50|min:0',
	]);

	if($validator->fails()){
	return redirect()->back()
	->withErrors($validator)
	->withInput();
	}
		
	 $id = $request->id;
	 $userprofile=Brand::find($id);
	$userprofile->type=$request->type;
	$userprofile->name=$request->name;
	$userprofile->save();
	\Session::put('success','Data Updated Successfully.');
	return redirect("edit-brand/$id");
	
	}
	
	/* ---------------- End Brand Management -------------------*/
	
	
	
	/* ---------------- Start Vehicle Type Managementt -------------------*/
	
	public function vehicletypes()
    {
		
	   $data['vehicletypes']=VehicleTypes::where('delete_status','0')->orderby('id','desc')->get();
       return view('admin.vehicletypes.index',$data);
    }
	
	
	public function add_vehicletypes()
    {

       return view('admin.vehicletypes.add');
    }
	
	public function post_add_vehicletypes(Request $request)
	
	{

	$validator = Validator::make($request->all(), [
	
	'type'=>'required|max:50|min:0',
	]);

	if($validator->fails()){
	return redirect()->back()
	->withErrors($validator)
	->withInput();
	}

	$userprofile=new VehicleTypes;
	$userprofile->type=$request->type;
	$userprofile->delete_status='0';
	$userprofile->status='0';
	$userprofile->save();
	
	\Session::put('success','Data Added Successfully.');
	return redirect()->route('vehicletypes');

	}
	
	
	public function delete_vehicletypes($id)
    {
		
	$userprofile=VehicleTypes::find($id);

	$userprofile->delete_status='1';

	$userprofile->save();
	\Session::put('success','Data Deletes Successfully.');
	return redirect()->route('vehicletypes');
		
	}
	
	public function edit_vehicletypes($id)
    {
		$data['info'] = VehicleTypes::find($id);
	    return view('admin.vehicletypes.detail',$data);
	}
	
	
	
	function post_edit_vehicletypes(Request $request)
	{
		
		$validator = Validator::make($request->all(), [
	
	'type'=>'required|max:50|min:0',
	]);

	if($validator->fails()){
	return redirect()->back()
	->withErrors($validator)
	->withInput();
	}
		
	 $id = $request->id;
	 $userprofile=VehicleTypes::find($id);
	$userprofile->type=$request->type;
	$userprofile->save();
	\Session::put('success','Data Updated Successfully.');
	return redirect("edit-vehicletypes/$id");
	
	}
	
	/* ---------------- End Vehicle Type Management -------------------*/
	
	
	/* ---------------- Start Fuel Type Managementt -------------------*/
	
	public function fueltype()
    {
		
	   $data['fueltype']=Fueltype::where('delete_status','0')->orderby('id','desc')->get();
       return view('admin.fueltype.index',$data);
    }
	
	
	public function add_fueltype()
    {
	   $data['vehicletypes']=VehicleTypes::where('delete_status','0')->orderby('id','desc')->get();

       return view('admin.fueltype.add',$data);
    }
	
	public function post_add_fueltype(Request $request)
	
	{

	$validator = Validator::make($request->all(), [
	'fuel_type'=>'required|max:50|min:0',
	]);

	if($validator->fails()){
	return redirect()->back()
	->withErrors($validator)
	->withInput();
	}

	$userprofile=new Fueltype;
	$userprofile->vehicle_type=$request->vehicle_type;
	$userprofile->fuel_type=$request->fuel_type;
	$userprofile->delete_status='0';
	$userprofile->status='0';
	$userprofile->save();
	
	\Session::put('success','Data Added Successfully.');
	return redirect()->route('fueltype');

	}
	
	
	public function delete_fueltype($id)
    {
		
	$userprofile=Fueltype::find($id);

	$userprofile->delete_status='1';

	$userprofile->save();
	\Session::put('success','Data Deletes Successfully.');
	return redirect()->route('fueltype');
		
	}
	
	public function edit_fueltype($id)
    {
		$data['info'] = Fueltype::find($id);
	    return view('admin.fueltype.detail',$data);
	}
	
	
	
	function post_edit_fueltype(Request $request)
	{
		$validator = Validator::make($request->all(), [
	'fuel_type'=>'required|max:50|min:0',
	]);

	if($validator->fails()){
	return redirect()->back()
	->withErrors($validator)
	->withInput();
	}
		
	 $id = $request->id;
	 $userprofile=Fueltype::find($id);
	$userprofile->fuel_type=$request->fuel_type;
	$userprofile->save();
	\Session::put('success','Data Updated Successfully.');
	return redirect("edit-fueltype/$id");
	
	}
	
	/* ---------------- End Fuel Type Management -------------------*/
	
	
	/* ---------------- Start Transmission Managementt -------------------*/
	
	public function transmission()
    {
		
	   $data['transmission']=Transmission::where('delete_status','0')->orderby('id','desc')->get();
       return view('admin.transmission.index',$data);
    }
	
	
	public function add_transmission()
    {
	   $data['vehicletypes']=VehicleTypes::where('delete_status','0')->orderby('id','desc')->get();

       return view('admin.transmission.add',$data);
    }
	
	public function post_add_transmission(Request $request)
	
	{

	$validator = Validator::make($request->all(), [
	'transmission'=>'required|max:50|min:0',
	]);

	if($validator->fails()){
	return redirect()->back()
	->withErrors($validator)
	->withInput();
	}

	$userprofile=new Transmission;
	$userprofile->vehicle_type=$request->vehicle_type;
	$userprofile->transmission=$request->transmission;
	$userprofile->delete_status='0';
	$userprofile->status='0';
	$userprofile->save();
	
	\Session::put('success','Data Added Successfully.');
	return redirect()->route('transmission');

	}
	
	
	public function delete_transmission($id)
    {
		
	$userprofile=Transmission::find($id);

	$userprofile->delete_status='1';

	$userprofile->save();
	\Session::put('success','Data Deletes Successfully.');
	return redirect()->route('transmission');
		
	}
	
	public function edit_transmission($id)
    {
		$data['info'] = Transmission::find($id);
	    return view('admin.transmission.detail',$data);
	}
	
	
	
	function post_edit_transmission(Request $request)
	{
		
		$validator = Validator::make($request->all(), [
	'transmission'=>'required|max:50|min:0',
	]);

	if($validator->fails()){
	return redirect()->back()
	->withErrors($validator)
	->withInput();
	}
		
	 $id = $request->id;
	 $userprofile=Transmission::find($id);
	$userprofile->transmission=$request->transmission;
	$userprofile->save();
	\Session::put('success','Data Updated Successfully.');
	return redirect("edit-transmission/$id");
	
	}
	
	/* ---------------- End Transmission Management -------------------*/
	
	
	/* ---------------- Start Residence Type Managementt -------------------*/
	
	public function residencetype()
    {
		
	   $data['residencetype']=Residence::where('delete_status','0')->orderby('id','desc')->get();
       return view('admin.residencetype.index',$data);
    }
	
	
	public function add_residencetype()
    {
       return view('admin.residencetype.add');
    }
	
	public function post_add_residencetype(Request $request)
	
	{

	$validator = Validator::make($request->all(), [
	'residencetype'=>'required|max:50|min:0',
	]);

	if($validator->fails()){
	return redirect()->back()
	->withErrors($validator)
	->withInput();
	}

	$userprofile=new Residence;
	$userprofile->residencetype=$request->residencetype;
	$userprofile->delete_status='0';
	$userprofile->status='0';
	$userprofile->save();
	
	\Session::put('success','Data Added Successfully.');
	return redirect()->route('residencetype');

	}
	
	
	public function delete_residencetype($id)
    {
		
	$userprofile=Residence::find($id);

	$userprofile->delete_status='1';

	$userprofile->save();
	\Session::put('success','Data Deletes Successfully.');
	return redirect()->route('residencetype');
		
	}
	
	public function edit_residencetype($id)
    {
		$data['info'] = Residence::find($id);
	    return view('admin.residencetype.detail',$data);
	}
	
	
	
	function post_edit_residencetype(Request $request)
	{
		
		$validator = Validator::make($request->all(), [
	'residencetype'=>'required|max:50|min:0',
	]);

	if($validator->fails()){
	return redirect()->back()
	->withErrors($validator)
	->withInput();
	}
		
	 $id = $request->id;
	 $userprofile=Residence::find($id);
	$userprofile->residencetype=$request->residencetype;
	$userprofile->save();
	\Session::put('success','Data Updated Successfully.');
	return redirect("edit-residencetype/$id");
	
	}
	
	/* ---------------- End Residence Type Management -------------------*/
	
	
	/* ---------------- Start Furnishing Type Managementt -------------------*/
	
	public function furnishingtype()
    {
		
	   $data['furnishingtype']=Furnishing::where('delete_status','0')->orderby('id','desc')->get();
       return view('admin.furnishingtype.index',$data);
    }
	
	
	public function add_furnishingtype()
    {
       return view('admin.furnishingtype.add');
    }
	
	public function post_add_furnishingtype(Request $request)
	
	{

	$validator = Validator::make($request->all(), [
	'furnishingtype'=>'required|max:50|min:0',
	]);

	if($validator->fails()){
	return redirect()->back()
	->withErrors($validator)
	->withInput();
	}
	$userprofile=new Furnishing;
	$userprofile->furnishingtype=$request->furnishingtype;
	$userprofile->delete_status='0';
	$userprofile->status='0';
	$userprofile->save();
	
	\Session::put('success','Data Added Successfully.');
	return redirect()->route('furnishingtype');

	}
	
	
	public function delete_furnishingtype($id)
    {
		
	$userprofile=Furnishing::find($id);

	$userprofile->delete_status='1';

	$userprofile->save();
	\Session::put('success','Data Deletes Successfully.');
	return redirect()->route('furnishingtype');
		
	}
	
	public function edit_furnishingtype($id)
    {
		$data['info'] = Furnishing::find($id);
	    return view('admin.furnishingtype.detail',$data);
	}
	
	
	
	function post_edit_furnishingtype(Request $request)
	{
		
		$validator = Validator::make($request->all(), [
	'furnishingtype'=>'required|max:50|min:0',
	]);

	if($validator->fails()){
	return redirect()->back()
	->withErrors($validator)
	->withInput();
	}
		
	 $id = $request->id;
	 $userprofile=Furnishing::find($id);
	$userprofile->furnishingtype=$request->furnishingtype;
	$userprofile->save();
	\Session::put('success','Data Updated Successfully.');
	return redirect("edit-furnishingtype/$id");
	
	}
	
	/* ---------------- End Furnishing Type Management -------------------*/
	
	/* ---------------- Start Facing Managementt -------------------*/
	public function facing()
    {
		
	   $data['facing']=Facing::where('delete_status','0')->orderby('id','desc')->get();
       return view('admin.facing.index',$data);
    }
    
    public function add_facing()
    {
       return view('admin.facing.add');
    }
    
    public function post_add_facing(Request $request)
	
	{

	$validator = Validator::make($request->all(), [
	'facingtype'=>'required|max:50|min:0',
	]);

	if($validator->fails()){
	return redirect()->back()
	->withErrors($validator)
	->withInput();
	}

	$userprofile=new Facing;
	$userprofile->facingtype=$request->facingtype;
	$userprofile->delete_status='0';
	$userprofile->status='0';
	$userprofile->save();
	
	\Session::put('success','Data Added Successfully.');
	return redirect()->route('facing');

	}
	
	public function delete_facing($id)
    {
		
	$userprofile=Facing::find($id);

	$userprofile->delete_status='1';

	$userprofile->save();
	\Session::put('success','Data Deletes Successfully.');
	return redirect()->route('facing');
		
	}
	
		public function edit_facing($id)
    {
		$data['info'] = Facing::find($id);
	    return view('admin.facing.detail',$data);
	}
	
	
	
	function post_edit_facing(Request $request)
	{
		
		$validator = Validator::make($request->all(), [
	'facingtype'=>'required|max:50|min:0',
	]);

	if($validator->fails()){
	return redirect()->back()
	->withErrors($validator)
	->withInput();
	}
		
	 $id = $request->id;
	 $userprofile=Facing::find($id);
	$userprofile->facingtype=$request->facingtype;
	$userprofile->save();
	\Session::put('success','Data Updated Successfully.');
	return redirect("edit-facing/$id");
	
	}
    
    /* ---------------- End Facing Management -------------------*/
	
	/* ---------------- Start Construction Managementt -------------------*/
	
	public function construction()
    {
		
	   $data['construction']=Construction::where('delete_status','0')->orderby('id','desc')->get();
       return view('admin.construction.index',$data);
    }
	
	
	public function add_construction()
    {
       return view('admin.construction.add');
    }
	
	public function post_add_construction(Request $request)
	
	{

	$validator = Validator::make($request->all(), [
	'constructiontype'=>'required|max:50|min:0',
	]);

	if($validator->fails()){
	return redirect()->back()
	->withErrors($validator)
	->withInput();
	}

	$userprofile=new Construction;
	$userprofile->constructiontype=$request->constructiontype;
	$userprofile->delete_status='0';
	$userprofile->status='0';
	$userprofile->save();
	
	\Session::put('success','Data Added Successfully.');
	return redirect()->route('construction');

	}
	
	
	public function delete_construction($id)
    {
		
	$userprofile=Construction::find($id);

	$userprofile->delete_status='1';

	$userprofile->save();
	\Session::put('success','Data Deletes Successfully.');
	return redirect()->route('construction');
		
	}
	
	public function edit_construction($id)
    {
		$data['info'] = Construction::find($id);
	    return view('admin.construction.detail',$data);
	}
	
	
	
	function post_edit_construction(Request $request)
	{
		
		$validator = Validator::make($request->all(), [
	'constructiontype'=>'required|max:50|min:0',
	]);

	if($validator->fails()){
	return redirect()->back()
	->withErrors($validator)
	->withInput();
	}
		
	 $id = $request->id;
	 $userprofile=Construction::find($id);
	$userprofile->constructiontype=$request->constructiontype;
	$userprofile->save();
	\Session::put('success','Data Updated Successfully.');
	return redirect("edit-construction/$id");
	
	}
	
	/* ---------------- End Construction Management -------------------*/
	
	
	/* ---------------- Start Job Managementt -------------------*/
	
	public function job()
    {
		
	   $data['job']=Job::where('delete_status','0')->orderby('id','desc')->get();
       return view('admin.job.index',$data);
    }
	
	
	public function add_job()
    {
       return view('admin.job.add');
    }
	
	public function post_add_job(Request $request)
	
	{

	$validator = Validator::make($request->all(), [
	'jobtype'=>'required|max:50|min:0',
	]);

	if($validator->fails()){
	return redirect()->back()
	->withErrors($validator)
	->withInput();
	}

	$userprofile=new Job;
	$userprofile->jobtype=$request->jobtype;
	$userprofile->delete_status='0';
	$userprofile->status='0';
	$userprofile->save();
	
	\Session::put('success','Data Added Successfully.');
	return redirect()->route('job');

	}
	
	
	public function delete_job($id)
    {
		
	$userprofile=Job::find($id);

	$userprofile->delete_status='1';

	$userprofile->save();
	\Session::put('success','Data Deletes Successfully.');
	return redirect()->route('job');
		
	}
	
	public function edit_job($id)
    {
		$data['info'] = Job::find($id);
	    return view('admin.job.detail',$data);
	}
	
	
	
	function post_edit_job(Request $request)
	{
		
		$validator = Validator::make($request->all(), [
	'jobtype'=>'required|max:50|min:0',
	]);

	if($validator->fails()){
	return redirect()->back()
	->withErrors($validator)
	->withInput();
	}
		
	 $id = $request->id;
	 $userprofile=Job::find($id);
	$userprofile->jobtype=$request->jobtype;
	$userprofile->save();
	\Session::put('success','Data Updated Successfully.');
	return redirect("edit-job/$id");
	
	}
	
	/* ---------------- End Job Management -------------------*/
	
	
	/* ---------------- Start Subscription Managementt -------------------*/
	
	public function subscription()
    {
		
	   $data['subscription']=Subscription::where('delete_status','0')->orderby('id','desc')->get();
       return view('admin.subscription.index',$data);
    }
    
    
    
    public function per_ads_costing()
    {
        $data['costing'] = DB::table('subscription_costing')->first();
        return view('admin.subscription.costing',$data);
    }
    
    public function post_ads_costing(Request $request)
    {
        DB::table('subscription_costing')->where('id','1')->update(array('ad_costing'=> $request->ad_costing));
        
		
		\Session::put('success','Data Added Successfully.');
	    return redirect()->route('per-ads-costing');
    }
    
    public function update_status_subscriber($id)
    {
        $subscriber = Subscription::find($id);
        if($subscriber->status == '0')
        {
            $subscriber->status = '1';
            $subscriber->save();
        }else{
            $subscriber->status = '0';
            $subscriber->save();
        }
        
        \Session::put('success','Data Added Successfully.');
	    return redirect()->route('subscription');
    }
	
	
	public function add_subscription()
    {
	   $data['categories']=Categories::where('delete_status','0')->orderby('id','desc')->get();

       return view('admin.subscription.add',$data);
    }
	
	public function post_add_subscription(Request $request)
	{
	    $validator = Validator::make($request->all(), [
	        'package'           => 'required|max:50|min:0',
        	'package_validity'  => 'required|max:50|min:0',
        	'no_of_ads'         => 'required|max:50|min:0',
        	'ads_validity'      => 'required|max:50|min:0',
        //	'ads_costing'       => 'required|max:50|min:0',
        	'mrp'               => 'required|max:50|min:0',
        	'discount'          => 'required|max:50|min:0',
        	'offered_price'     => 'required|max:50|min:0',
	    ]);

	    if($validator->fails()){
	        return redirect()->back()->withErrors($validator)->withInput();
	    }
	    
	    if(isset($request->is_free))
	    {
	        $is_free = 'yes';
	    }else{
	        $is_free = 'no';
	    }

    	$userprofile=new Subscription;
    	$userprofile->category_id       = implode(",",$request->category_id);
    	$userprofile->package           = $request->package;
    	$userprofile->package_validity  = $request->package_validity;
    	$userprofile->no_of_ads         = $request->no_of_ads;
    	$userprofile->ads_validity      = $request->ads_validity;
    	$userprofile->ads_costing       = $request->ads_costing;
    	$userprofile->mrp               = $request->mrp;
    	$userprofile->discount          = $request->discount;
    	$userprofile->offered_price     = $request->offered_price;
    	$userprofile->is_free           = $is_free;
    	$userprofile->delete_status     = '0';
    	$userprofile->status            = '0';
    	$userprofile->subscription_number            = 'SUB'.rand(1000,9999);
    	$userprofile->save();
	
    	\Session::put('success','Data Added Successfully.');
    	return redirect()->route('subscription');

	}
	
	
	public function delete_subscription($id)
    {
		
	$userprofile=Subscription::find($id);

	$userprofile->delete_status='1';

	$userprofile->save();
	\Session::put('success','Data Deletes Successfully.');
	return redirect()->route('subscription');
		
	}
	
	public function edit_subscription($id)
    {
		$data['info'] = Subscription::find($id);
		$data['categories'] = Categories::where('delete_status',0)->where('status',0)->get();
	    return view('admin.subscription.detail',$data);
	}
	
	
	
	function post_edit_subscription(Request $request)
	{
	  
	    $validator = Validator::make($request->all(), [
	        'package'           =>'required|max:50|min:0',
        	'no_of_ads'         =>'required|max:50|min:0',
        	'ads_validity'      =>'required|max:50|min:0',
        	'mrp'               =>'required|max:50|min:0',
        	'discount'          =>'required|max:50|min:0',
        	'offered_price'     =>'required|max:50|min:0',
        	'category_id'       => 'required|array',
	]);
	if($validator->fails()){
	    return redirect()->back()->withErrors($validator)->withInput();
	}
	$id                         = $request->id;
	$userprofile                = Subscription::find($id);
	$userprofile->category_id   = implode(',', $request->category_id);
	$userprofile->package       = $request->package;
	$userprofile->package_validity  = $request->package_validity;
	$userprofile->no_of_ads     = $request->no_of_ads;
	$userprofile->ads_validity  = $request->ads_validity;
	$userprofile->ads_costing   = $request->ads_costing;
	$userprofile->mrp           = $request->mrp;
	$userprofile->discount      = $request->discount;
	$userprofile->offered_price = $request->offered_price;
	if(isset($request->is_free))
	{
	    $userprofile->is_free = 'yes';
	}else{
	    $userprofile->is_free = 'no';
	}
	$userprofile->delete_status = '0';
	$userprofile->status        = '0';
	$userprofile->save();
//	$userprofile->save();
	\Session::put('success','Data Updated Successfully.');
	return redirect("edit-subscription/$id");
	
	}
	
	/* ---------------- End Subscription Management -------------------*/
	
	
	/* ---------------- Start Free Trail Subscription Managementt -------------------*/
	
	public function freetrail()
    {
		
	   $data['freetrail']=Freetrail::where('delete_status','0')->orderby('id','desc')->get();
       return view('admin.freetrail.index',$data);
    }
	
	
	public function add_freetrail()
    {
	   $data['categories']=Categories::where('delete_status','0')->orderby('id','desc')->get();

       return view('admin.freetrail.add',$data);
    }
	
	public function post_add_freetrail(Request $request)
	
	{

	$validator = Validator::make($request->all(), [
	'no_of_ads'=>'required|max:50|min:0',
	'ads_validity'=>'required|max:50|min:0',
	]);

	if($validator->fails()){
	return redirect()->back()
	->withErrors($validator)
	->withInput();
	}
	
	$result = DB::table('subscriptions_free_trials')->where('category_id',$request->category_id)->exists();
    if($result){
		\Session::put('success','Category Atready Added');
		return redirect("freetrail");
	}

	$userprofile=new Freetrail;
	$userprofile->category_id=$request->category_id;
	$userprofile->no_of_ads=$request->no_of_ads;
	$userprofile->ads_validity=$request->ads_validity;
	$userprofile->delete_status='0';
	$userprofile->status='0';
	$userprofile->save();
	
	\Session::put('success','Data Added Successfully.');
	return redirect()->route('freetrail');

	}
	
	
	public function delete_freetrail($id)
    {
		
	$userprofile=Freetrail::find($id);

	$userprofile->delete_status='1';

	$userprofile->save();
	\Session::put('success','Data Deletes Successfully.');
	return redirect()->route('freetrail');
		
	}
	
	public function edit_freetrail($id)
    {
		$data['info'] = Freetrail::find($id);
	    return view('admin.freetrail.detail',$data);
	}
	
	
	
	function post_edit_freetrail(Request $request)
	{
		
		$validator = Validator::make($request->all(), [
	'no_of_ads'=>'required|max:50|min:0',
	'ads_validity'=>'required|max:50|min:0',
	]);

	if($validator->fails()){
	return redirect()->back()
	->withErrors($validator)
	->withInput();
	}
		
	 $id = $request->id;
	 $userprofile=Freetrail::find($id);
	$userprofile->category_id=$request->category_id;
	$userprofile->no_of_ads=$request->no_of_ads;
	$userprofile->ads_validity=$request->ads_validity;
	$userprofile->delete_status='0';
	$userprofile->status='0';
	$userprofile->save();
	\Session::put('success','Data Updated Successfully.');
	return redirect("edit-freetrail/$id");
	
	}
	
	/* ---------------- End Free Trail Subscription Management -------------------*/
	
	public function subscription_order()
    {
		
	   $data['subscription_order']=SubscriptionOrder::where('delete_status','0')->orderby('id','desc')->get();
       return view('admin.subscription_history.index',$data);
    }
	
	public function subscription_history()
    {
	
	   $data['subscription_order']=SubscriptionOrder::where('delete_status','0')->orderby('id','desc')->get();
       return view('admin.subscription_history.subscription_history',$data);
    }
	
	public function subscription_order_payment_status($id)
    {
		
	   DB::table('subscription_orders')->where('id',$id)->update(
		array(
		'payment_status'=>'Completed',
		));
	   
		\Session::put('success','Status Updated Successfully.');
		return redirect("subscription-order");
	   
    }
    
    public function transaction_history()
    {
		
	   $data['transaction_history']=SubscriptionOrder::where('delete_status','0')->orderby('id','desc')->get();
       return view('admin.transaction.history',$data);
    }
    
    public function view_transction_history($id)
    {
        $data['transaction_history']    = SubscriptionOrder::find($id);
        $data['admin_detail']           = User::first();
        @$data['customer_detail']        = Customer::with('cities','states','countries')->find($data['transaction_history']->user_id);
        @$data['subscription']           = Subscription::find($data['transaction_history']->subscription_id);
        return view('admin.transaction.invoice',$data);
    }
	
	/* ---------------- Start Blog Managementt -------------------*/
	
	public function blog()
    {
		
	   $data['blog']=Blog::where('delete_status','0')->orderby('id','desc')->get();
       return view('admin.blog.index',$data);
    }
	
	
	public function add_blog()
    {
	   $data['blog']=Blog::where('delete_status','0')->orderby('id','desc')->get();

       return view('admin.blog.add',$data);
    }
	
	public function post_add_blog(Request $request)
	
	{

	$validator = Validator::make($request->all(), [
	'title'=>'required|max:2000|min:0',
	'description'=>'required|max:2000|min:0',
	'file' => 'mimes:jpg,jpeg,png,svg|max:2048',
	]);

	if($validator->fails()){
	return redirect()->back()
	->withErrors($validator)
	->withInput();
	}

	$userprofile=new Blog;
	
	if($request->file('file')){
	$imageName = time().'.'.$request->file->extension();
	$request->file->move(public_path('uploads/blogs'),$imageName);
	$userprofile->image=url('public/uploads/blogs').'/'.$imageName;
	}else{
	$userprofile->image=url('public/uploads/blogs/dummy.jpeg'); 
	} 
	
	$userprofile->title=$request->title;
	$userprofile->description=$request->description;
	$userprofile->delete_status='0';
	$userprofile->status='0';
	$userprofile->save();
	
	\Session::put('success','Data Added Successfully.');
	return redirect()->route('blog');

	}
	
	
	public function delete_blog($id)
    {
		
	$userprofile=Blog::find($id);

	$userprofile->delete_status='1';

	$userprofile->save();
	\Session::put('success','Data Deletes Successfully.');
	return redirect()->route('blog');
		
	}
	
	public function edit_blog($id)
    {
		$data['info'] = Blog::find($id);
	    return view('admin.blog.detail',$data);
	}
	
	
	
	function post_edit_blog(Request $request)
	{
		
		$validator = Validator::make($request->all(), [
	'title'=>'required|max:2000|min:0',
	'description'=>'required|max:2000|min:0',
	'file' => 'mimes:jpg,jpeg,png,svg|max:2048',
	]);

	if($validator->fails()){
	return redirect()->back()
	->withErrors($validator)
	->withInput();
	}
		
	 $id = $request->id;
	 $old_image = $request->old_image;
	 
	 $userprofile=Blog::find($id);
	 
	if($request->file('file')){
	$imageName = time().'.'.$request->file->extension();
	$request->file->move(public_path('uploads/blogs'),$imageName);
	$userprofile->image=url('public/uploads/blogs').'/'.$imageName;
	}else{
	$userprofile->image=$old_image;
	} 
	
	$userprofile->title=$request->title;
	$userprofile->description=$request->description;
	$userprofile->save();
	\Session::put('success','Data Updated Successfully.');
	return redirect("edit-blog/$id");
	
	}
	
	/* ---------------- End Blog Management -------------------*/
	
	
	/* ---------------- Start Contact Us Management -------------------*/
	
	public function contact_details()
    {
		
		$data['info'] = Contact::find('1');
		return view('admin.contact.index',$data);
    }
	
	public function update_contact_details(Request $request)
    {
	
		DB::table('contact_us')->where('id','1')->update(
		array(
		'name'=>$request->name,
		'mobile'=>$request->mobile,
		'email'=>$request->email,
		'location'=>$request->location
		
		));
		\Session::put('success','Data Updated Successfully.');
		return redirect("contact-details");
    }
	
	public function update_contact_logo(Request $request)
    {
	
		//$userprofile=new AdminProfile;
		$userprofile=Contact::find('1');
	
	
	if($request->file('file')){
	$imageName = time().'.'.$request->file->extension();
	$request->file->move(public_path('uploads/contact'),$imageName);
	$userprofile->image=url('public/uploads/contact').'/'.$imageName;
	}else{
	$userprofile->image=url('public/uploads/contact/dummy.jpeg'); 
	} 
 
	$userprofile->save();
	\Session::put('success','Data Updated Successfully.');
		return redirect("contact-details");
    }
	
	
	/* ---------------- End Contact Us Management -------------------*/
	
	/* ---------------- Start FAQ Category Management -------------------*/
	
	public function faqcategory()
    {
	   $data['faqcategory']=Faqcategory::where('delete_status','0')->orderby('id','desc')->get();
       return view('admin.faqcategory.index',$data);
    }
	
	
	public function add_faqcategory()
    {

       return view('admin.faqcategory.add');
    }
	
	public function post_add_faqcategory(Request $request)
	
	{

	$validator = Validator::make($request->all(), [
	
	'name'=>'required|max:500|min:0',
	]);

	if($validator->fails()){
	return redirect()->back()
	->withErrors($validator)
	->withInput();
	}

	$userprofile=new Faqcategory;
	$userprofile->name=$request->name;
	$userprofile->delete_status='0';
	$userprofile->status='0';
	$userprofile->save();
	
	\Session::put('success','Data Added Successfully.');
	return redirect()->route('faqcategory');

	}
	
	
	public function delete_faqcategory($id)
    {
		
	$userprofile=Faqcategory::find($id);

	$userprofile->delete_status='1';

	$userprofile->save();
	\Session::put('success','Data Deletes Successfully.');
	return redirect()->route('faqcategory');
		
	}
	
	public function edit_faqcategory($id)
    {
		$data['info'] = Faqcategory::find($id);
	    return view('admin.faqcategory.detail',$data);
	}
	
	
	
	function post_edit_faqcategory(Request $request)
	{
		
		$validator = Validator::make($request->all(), [
	
	'name'=>'required|max:50|min:0',
	]);

	if($validator->fails()){
	return redirect()->back()
	->withErrors($validator)
	->withInput();
	}
		
	 $id = $request->id;
	 $userprofile=Faqcategory::find($id);
	$userprofile->name=$request->name;
	$userprofile->save();
	\Session::put('success','Data Updated Successfully.');
	return redirect("edit-faqcategory/$id");
	
	}
	
	/* ---------------- End FAQ Category Management -------------------*/
	
	
	/* ---------------- Start FAQ Management -------------------*/
	
	public function faq()
    {
	   $data['faq']=Faq::where('delete_status','0')->orderby('id','desc')->get();
       return view('admin.faq.index',$data);
    }
	
	
	public function add_faq()
    {
	   $data['faqcategory']=Faqcategory::where('delete_status','0')->orderby('id','desc')->get();
	   return view('admin.faq.add',$data);
    }
	
	public function post_add_faq(Request $request)
	
	{

	$validator = Validator::make($request->all(), [
	'question'=>'required|max:1200|min:0',
	'answer'=>'required|max:2200|min:0',
	]);

	if($validator->fails()){
	return redirect()->back()
	->withErrors($validator)
	->withInput();
	}

	$userprofile=new Faq;
	$userprofile->category_id=$request->category_id;
	$userprofile->question=$request->question;
	$userprofile->answer=$request->answer;
	$userprofile->delete_status='0';
	$userprofile->status='0';
	$userprofile->save();
	
	\Session::put('success','Data Added Successfully.');
	return redirect()->route('faq');

	}
	
	
	public function delete_faq($id)
    {
		
	$userprofile=Faq::find($id);

	$userprofile->delete_status='1';

	$userprofile->save();
	\Session::put('success','Data Deletes Successfully.');
	return redirect()->route('faq');
		
	}
	
	public function edit_faq($id)
    {
		$data['info'] = Faq::find($id);
		$data['faqcategory'] = DB::table('faq_category')->get();
	    return view('admin.faq.detail',$data);
	}
	
	
	
	function post_edit_faq(Request $request)
	{
		
		$validator = Validator::make($request->all(), [
    'category_id' => 'required',
	'question'=>'required|max:1200|min:0',
	'answer'=>'required|max:2200|min:0',
	]);

	if($validator->fails()){
	return redirect()->back()
	->withErrors($validator)
	->withInput();
	}
		
	 $id = $request->id;
	 $userprofile=Faq::find($id);
	$userprofile->category_id=$request->category_id;
	$userprofile->question=$request->question;
	$userprofile->answer=$request->answer;
	$userprofile->save();
	\Session::put('success','Data Updated Successfully.');
	return redirect("edit-faq/$id");
	
	}
	
	/* ---------------- End FAQ  Management -------------------*/
	
	
	/* ----------------Start About Management -------------------*/
	
	
	public function about()
    {
	   $data['about']=About::where('delete_status','0')->orderby('id','desc')->get();
       return view('admin.about.index',$data);
    }
	
	
	
	public function add_about()
    {
       return view('admin.about.add');
    }
	
	
	public function post_add_about(Request $request)
	
	{

	$validator = Validator::make($request->all(), [
	'heading'=>'required|max:50|min:0',
	'description'=>'required|max:2000|min:0',
	//'file' => 'required|mimes:jpg,jpeg,png,svg|max:2048',
	]);

	if($validator->fails()){
	return redirect()->back()
	->withErrors($validator)
	->withInput();
	}

	
	$userprofile=new About;
	
	
	if($request->file('file')){
	$imageName = time().'.'.$request->file->extension();
	$request->file->move(public_path('uploads/about'),$imageName);
	$userprofile->imageone=url('public/uploads/about').'/'.$imageName;
	}else{
	$userprofile->imageone=url('public/uploads/about/dummy.jpeg'); 
	} 
	
	
	if($request->file('filetwo')){
	$imageNametwo = time().'.'.$request->filetwo->extension();
	$request->filetwo->move(public_path('uploads/about'),$imageNametwo);
	$userprofile->imagetwo=url('public/uploads/about').'/'.$imageNametwo;
	}else{
	$userprofile->imagetwo=url('public/uploads/about/dummy.jpeg'); 
	} 
 
	$userprofile->heading=$request->heading;
	$userprofile->description=$request->description;
	$userprofile->delete_status='0';
	$userprofile->status='0';
	$userprofile->save();
	
	\Session::put('success','Data Added Successfully.');
	return redirect()->route('about');

	}
	
	public function edit_about($id)
    {
		$data['info'] = About::find($id);
	    return view('admin.about.detail',$data);
	}
	
	
	
	function post_edit_about(Request $request)
	{
		
		$validator = Validator::make($request->all(), [
	'heading'=>'required|max:50|min:0',
	'description'=>'required|max:2000|min:0',
	//'file' => 'required|mimes:jpg,jpeg,png,svg|max:2048',
	]);

	if($validator->fails()){
	return redirect()->back()
	->withErrors($validator)
	->withInput();
	}
		
	 $id = $request->id;
	 $old_image = $request->old_image;
	 $old_imagetwo = $request->old_imagetwo;
	 
	 $userprofile=About::find($id);
	 
	if($request->file('file')){
	$imageName = time().'.'.$request->file->extension();
	$request->file->move(public_path('uploads/about'),$imageName);
	$userprofile->imageone=url('public/uploads/about').'/'.$imageName;
	}else{
	$userprofile->imageone=$old_image;
	} 
	
	
	if($request->file('filetwo')){
	$imageNametwo = time().'.'.$request->filetwo->extension();
	$request->filetwo->move(public_path('uploads/about'),$imageNametwo);
	$userprofile->imagetwo=url('public/uploads/about').'/'.$imageNametwo;
	}else{
	$userprofile->imagetwo=$old_imagetwo;
	} 
	
	$userprofile->heading=$request->heading;
	$userprofile->description=$request->description;
	$userprofile->save();
	\Session::put('success','Data Updated Successfully.');
	return redirect("edit-about/$id");
	
	}
	
	
	public function delete_about($id)
    {
		
	$userprofile=About::find($id);

	$userprofile->delete_status='1';

	$userprofile->save();
	\Session::put('success','Data Deletes Successfully.');
	return redirect()->route('about');
		
	}
	
	/* ---------------- End About Management -------------------*/
	
	
	/* ---------------- Start Pages Management -------------------*/
	
	public function pages()
    {
	   $data['pages']=Pages::where('delete_status','0')->orderby('id','desc')->get();
       return view('admin.pages.index',$data);
    }
	
	
	public function add_pages()
    {

       return view('admin.pages.add');
    }
	
	public function post_add_pages(Request $request)
	
	{

	$validator = Validator::make($request->all(), [
	
	'name'=>'required|max:2000|min:0',
	'heading'=>'required|max:2000|min:0',
	'description'=>'required|max:2000|min:0',
	]);

	if($validator->fails()){
	return redirect()->back()
	->withErrors($validator)
	->withInput();
	}
	
	$name = $request->name;
    $string = preg_replace('/\s+/','-', $name);	
	//echo $url =  preg_replace('/[^A-Za-z0-9\-]/', '', $name);
	//echo $string = preg_replace('/\s+/','-', $url);
	//echo $updates_url =  strtolower($url);
	//$url =  preg_replace('/[^A-Za-z0-9\-]/', '', $name);
    $updates_url =  strtolower($string);
	$rand = mt_rand(1500, 5000);
	$new_url = 'pages-'.$updates_url;
	
	$result_url = DB::table('pages')->where('url',$new_url)->exists();
	if($result_url){
		\Session::put('success','URL already exists.');
	return redirect()->route('pages');
	}

	$userprofile=new Pages;
	$userprofile->name=$request->name;
	$userprofile->url=$new_url;
	$userprofile->heading=$request->heading;
	$userprofile->description=$request->description;
	$userprofile->delete_status='0';
	$userprofile->status='0';
	$userprofile->save();
	
	\Session::put('success','Data Added Successfully.');
	return redirect()->route('pages');

	}
	
	
	public function delete_pages($id)
    {
		
	$userprofile=Pages::find($id);

	$userprofile->delete_status='1';

	$userprofile->save();
	\Session::put('success','Data Deletes Successfully.');
	return redirect()->route('pages');
		
	}
	
	public function edit_pages($id)
    {
		$data['info'] = Pages::find($id);
	    return view('admin.pages.detail',$data);
	}
	
	
	
	function post_edit_pages(Request $request)
	{
		
		$validator = Validator::make($request->all(), [
	
	'name'=>'required|max:2000|min:0',
	'heading'=>'required|max:2000|min:0',
	'description'=>'required|max:2000|min:0',
	]);

	if($validator->fails()){
	return redirect()->back()
	->withErrors($validator)
	->withInput();
	}
		
	 $id = $request->id;
	 $userprofile=Pages::find($id);
	$userprofile->name=$request->name;
	$userprofile->heading=$request->heading;
	$userprofile->description=$request->description;
	$userprofile->save();
	\Session::put('success','Data Updated Successfully.');
	return redirect("edit-pages/$id");
	
	}
	
	/* ---------------- End Pages Management -------------------*/
	
	
	/* ---------------- Start Form Type Management -------------------*/
	
	public function formtype()
    {
	   $data['formtype']=Formtype::where('delete_status','0')->orderby('id','desc')->get();
       return view('admin.formtype.index',$data);
    }
	
	
	public function add_formtype()
    {

       return view('admin.formtype.add');
    }
	
	public function post_add_formtype(Request $request)
	
	{

	$validator = Validator::make($request->all(), [
	
	'type'=>'required|max:50|min:0',
	]);

	if($validator->fails()){
	return redirect()->back()
	->withErrors($validator)
	->withInput();
	}

	$userprofile=new Formtype;
	$userprofile->type=$request->type;
	$userprofile->delete_status='0';
	$userprofile->status='0';
	$userprofile->save();
	
	\Session::put('success','Data Added Successfully.');
	return redirect()->route('formtype');

	}
	
	
	public function delete_formtype($id)
    {
		
	$userprofile=Formtype::find($id);

	$userprofile->delete_status='1';

	$userprofile->save();
	\Session::put('success','Data Deletes Successfully.');
	return redirect()->route('formtype');
		
	}
	
	public function edit_formtype($id)
    {
		$data['info'] = Formtype::find($id);
	    return view('admin.formtype.detail',$data);
	}
	
	
	
	function post_edit_formtype(Request $request)
	{
		
		$validator = Validator::make($request->all(), [
	
	'type'=>'required|max:50|min:0',
	]);

	if($validator->fails()){
	return redirect()->back()
	->withErrors($validator)
	->withInput();
	}
		
	 $id = $request->id;
	 $userprofile=Formtype::find($id);
	$userprofile->type=$request->type;
	$userprofile->save();
	\Session::put('success','Data Updated Successfully.');
	return redirect("edit-formtype/$id");
	
	}
	
	/* ---------------- End Form Type Management -------------------*/
	
	
	/* ---------------- Start ADS  Management -------------------*/
	
	/*public function ads()
    {
	   $data['pending']=Jobforms::where('delete_status','0')->where('status','0')->orderby('id','desc')->get();
	   $data['published']=Jobforms::where('delete_status','0')->where('status','1')->orderby('id','desc')->get();
	   $data['rejected']=Jobforms::where('delete_status','0')->where('status','2')->orderby('id','desc')->get();
       return view('admin.jobads.ads',$data);
    }*/
	
	
	public function ads()
    {
	   //$data['pending']=Adposting::where('ad_expiry' , '=', NULL)->where('delete_status','0')->where('status','0')->orderby('id','desc')->get();
	   $data['pending']=Adposting::where('delete_status','0')->where('status','0')->orderby('id','desc')->get();
	   $data['published']=Adposting::where('delete_status','0')->where('status','1')->orderby('id','desc')->get();
	   $data['rejected']=Adposting::where('delete_status','0')->where('status','2')->orderby('id','desc')->get();
       return view('admin.userads.ads',$data);
    }
	
	
	public function reject_post(Request $request)
	
	{

	$id = $request->proff_id;
	$status = $request->status;
	$total_ads = '';
	
	$description = $request->description;
	$userprofile=Adposting::find($id);
	$userprofile->status=$request->status;
	$userprofile->reason=$request->reason;
	$userprofile->save();
	
	$result = DB::table('ads_postings')->where('id',$id)->get();
	$user_id = $result[0]->user_id;
	$category_id = $result[0]->category_id;
	$result_subscriber_exists = DB::table('subscription_orders')->where('user_id',$user_id)->where('category_id',$category_id)->exists();
	if($result_subscriber_exists){
	$result_subscriber = DB::table('subscription_orders')->where('user_id',$user_id)->where('category_id',$category_id)->get();
	$used_ads = $result_subscriber[0]->used_ads;
	if($used_ads > 0){
		$total_ads = $used_ads - 1;
	}
	DB::table('subscription_orders')->where('user_id',$user_id)->where('category_id',$category_id)->update(
    	array(
    	'used_ads'=>$total_ads,
    	));
	}
	$user = Customer::findOrFail($result[0]->user_id);
	if(isset($user->fcm_token)){
        $title = 'So, Sorry';
        $body = 'Your ad with id '.$result[0]->ad_id.' got rejected, because of the reason '.$request->reason;
        
        $image = null;
        $response = $this->sendNotification($title, $body, $user->fcm_token, $image);
    }
    $event = DefaultNotification::where('event', 'ad_rejected')->first();
    if(!empty($event))
    {
        $title = $event->title;
        $content = $event->content;
        $body = str_replace("#ad_id",$result[0]->ad_id, $content);
        $notifyArray=array(
            'user_id' => $result[0]->user_id,
            'event_name' => $event->event,
            'title' => $title,
            'body' => $body,
        );
    
        $this->singleUserNotification($notifyArray);
    }
	\Session::put('success','Ad Rejected Successfully.');
	return redirect("ads");

	}
	
	public function job_ads()
    {
	   $data['jobads']=Jobforms::where('delete_status','0')->where('status','0')->orderby('id','desc')->get();
       return view('admin.jobads.index',$data);
    }
	
	
	public function published_job_ads()
    {
	   $data['jobads']=Jobforms::where('delete_status','0')->where('status','1')->orderby('id','desc')->get();
       return view('admin.jobads.index',$data);
    }
	
	public function rejected_job_ads()
    {
	   $data['jobads']=Jobforms::where('delete_status','0')->where('status','2')->orderby('id','desc')->get();
       return view('admin.jobads.index',$data);
    }
	
	
	public function delete_job_ads($id)
    {
		
    	$userprofile=Jobforms::find($id);
    
    	$userprofile->delete_status='1';
    
    	$userprofile->save();
    	\Session::put('success','Data Deletes Successfully.');
    	return redirect("job-ads");
		
	}
	
	
	
	public function update_job_ad_status($id,$status)
    {
		
		
	$userprofile=Jobforms::find($id);
     
	if($status == '0'){
	   $userprofile->status='1';	
	}else{
	   $userprofile->status='0';
	}
	
	$userprofile->save();
	\Session::put('success','Status Updated Successfully.');
	$previous = url()->previous();
	return redirect($previous);
	//return redirect($previous);
		
	}
	
	public function view_job_ads($id)
    {
		$data['info'] = Jobforms::find($id);
	    return view('admin.jobads.view',$data);
	}
	
	
	public function change_job_ad_status(Request $request)
	
	{

    	$id = $request->proff_id;
    	$status = $request->status;
    	$result = DB::table('ads_postings')->where('id',$id)->get();
    	$resultad = DB::table('ads_postings')->where('id',$id)->first();
    	
                
    	if($status  = '1'){
    	    
    	
    	    $user = Customer::where('id', $resultad->user_id)->first();
    	
    	    $parent = Customer::where('id', $user->parent_id)->first();
    	    $child = Customer::where('parent_id', $user->id)->first();
    	    
    		$ads_validity = $result[0]->ads_validity;
    		$user_id = $result[0]->user_id;
    		$category_id = $result[0]->category_id;
    		$adCategory = Categories::where('id', $category_id)->first();
    		
            $subscriber_history_check  = DB::table('subscription_history')->where('user_id',$user_id)->orderBy('created_at','DESC')->whereDate('subscription_expiry','>=',date('Y-m-d'))->exists();
    		if($subscriber_history_check){
        		$subscriber_history  = DB::table('subscription_history')->where('user_id',$user_id)->whereDate('subscription_expiry','>=',date('Y-m-d'))->where('status',0)->orderBy('created_at','DESC')->first();
        		if($ads_validity != null){
        			$no = $ads_validity;	
        			$dates =  date("d-m-Y");
        			DB::table('ads_postings')->where('id',$id)->update(
            		array(
            		'ad_expiry'=>$subscriber_history->subscription_expiry,
            		'published_date' => $dates,
            		'active_status'=>'1'
            		));
        		}
    		}
    		
    		$category_subscription_result = DB::table("subscription_history")
                ->whereRaw("find_in_set('".$category_id."', subscription_history.category_id)")
                ->where('user_id', $user_id)
                ->where('status', '0')
                ->orderBy('created_at', 'desc')
                ->get();
            
            foreach ($category_subscription_result as $subscription) {
                if($status=='1'){
                    if ($subscription->used_ads < $subscription->remaining_ads) {
                        $new_used_ads = $subscription->used_ads + 1;
                
                        DB::table("subscription_history")
                            ->where('id', $subscription->id)
                            ->update(['used_ads' => $new_used_ads]);
                        break;
                    }
                }else if($status=='0'){
                    $new_used_ads = $subscription->used_ads - 1;
                    DB::table("subscription_history")
                        ->where('id', $subscription->id)
                        ->update(['used_ads' => $new_used_ads]);
                    break;
                }
                
            }
    		
    		
    	}
    	if(isset($user->fcm_token)){
            if($status == '1'){
                $title = 'Congratulations';
                $body = 'Your ad with id '.$result[0]->ad_id.' is approved.';
            }
            
            $image = null;
            $response = $this->sendNotification($title, $body, $user->fcm_token, $image);
        }
        $event = DefaultNotification::where('event', 'ad_published')->first();
        if(!empty($event) && !empty($resultad))
        {
            $title = $event->title;
            $content = $event->content;
            $body = str_replace("#ad_id",$result[0]->ad_id, $content);
            $notifyArray=array(
                'user_id' => $resultad->user_id,
                'event_name' => $event->event,
                'title' => $title,
                'body' => $body,
            );
        
            $this->singleUserNotification($notifyArray);
        }
        if(!empty($adCategory) && isset($parent) && !empty($parent))
        {
            $event1 = DefaultNotification::where('event', 'ad_published_by_seed')->first();
            if(!empty($event1))
            {
                $title = str_replace("#member_id",$user->member_id, $event1->title);
                $content = $event1->content;
                $body = str_replace("#date", date('m-d-Y'), (str_replace("#category_name", $adCategory->name, (str_replace("#member_id",$user->member_id, $content)))));
                $notifyArray1=array(
                    'user_id' => $parent->id,
                    'event_name' => $event1->event,
                    'title' => $title,
                    'body' => $body,
                );
            
                $this->singleUserNotification($notifyArray1);
            }
        }
        if(isset($child) && !empty($child) && !empty($resultad))
        {
            $event2 = DefaultNotification::where('event', 'ad_published_by_parent')->first();
            if(!empty($event2))
            {
                $link = url('ads-details/'.$resultad->id);
                
                $title = str_replace("#member_id",$user->member_id, $event2->title);
                $content = $event2->content;
                $body = str_replace('#title', $resultad->ad_title, (str_replace("#member_name", $user->name, (str_replace("#link", $link, (str_replace("#member_id",$user->member_id, $content)))))));

                $notifyArray2=array(
                    'user_id' => $child->id,
                    'event_name' => $event2->event,
                    'title' => $title,
                    'body' => $body,
                );
            
                $this->singleUserNotification($notifyArray2);
            }
        }
    	$userprofile=Adposting::find($id);
    	$userprofile->status=$request->status;
    	$userprofile->save();
    	echo 'done';

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
	
	public function edit_job_ads($id)
    {
		$data['info'] = Jobforms::find($id);
	    return view('admin.jobads.detail',$data);
	}
	
	function post_edit_job_ads(Request $request)
	{
		
		$validator = Validator::make($request->all(), [
	
	'salary_period'=>'required|max:50|min:0',
	'position_type'=>'required|max:50|min:0',
	'salary_from'=>'required|max:50|min:0',
	'salary_to'=>'required|max:50|min:0',
	'ad_title'=>'required|max:50|min:0',
	'ad_type'=>'required|max:50|min:0',
	'description'=>'required|max:50|min:0',
	]);

	if($validator->fails()){
	return redirect()->back()
	->withErrors($validator)
	->withInput();
	}
		
	 $id = $request->id;
	 $userprofile=Jobforms::find($id);
	$userprofile->salary_period=$request->salary_period;
	$userprofile->position_type=$request->position_type;
	$userprofile->salary_from=$request->salary_from;
	$userprofile->salary_to=$request->salary_to;
	$userprofile->ad_title=$request->ad_title;
	$userprofile->ad_type=$request->ad_type;
	$userprofile->description=$request->description;
	$userprofile->save();
	\Session::put('success','Data Updated Successfully.');
	return redirect("edit-job-ads/$id");
	
	}
	
	
	
	
	/* ---------------- End ADS Management -------------------*/
	
	
	/* ---------------- Start ADS Enquiry Management -------------------*/
	
	public function ads_enquiry()
    {
	   $data['adsenquiry']=Adsenquiry::orderby('id','desc')->get();
	   
       return view('admin.adsenquiry.index',$data);
    }
	
	/* ---------------- End ADS Enquiry Management -------------------*/
	
	/* ---------------- Start Enquiry Management -------------------*/
	
	public function enquiry()
    {
	   $data['enquiry']=Enquiry::orderby('id','desc')->get();
	   
       return view('admin.enquiry.index',$data);
    }
	
	/* ---------------- End Enquiry Management -------------------*/
	
	/* ------------------ Start RazorPay Setting -----------------*/
	public function payment_setting(Request $request)
	{
	    $setting = RazorpaySetting::first();
		$cashsetting = CashFreeSetting::first();
	    return view('admin.payment.index')->with(['setting'=>$setting,'cashsetting'=>$cashsetting]);
	}
	
	public function update_razorpay_setting(Request $request)
	{
	    $setting = RazorpaySetting::first();
	    
	    $setting->key_id = $request->key_id;
	    $setting->secret_id = $request->secret_id;
	    $setting->save();
	    
	    \Session::put('success','Data Updated Successfully.');
	    return redirect("payment-setting");
	    
	}

	public function update_cashfree_setting(Request $request)
	{
	    $setting = CashFreeSetting::first();
	    
	    $setting->key_id = $request->key_id_cash;
	    $setting->secret_id = $request->secret_id_cash;
	    $setting->save();
	    
	    \Session::put('success','Data Updated Successfully.');
	    return redirect("payment-setting");
	    
	}
	/* ------------------- End RazorPay Setting ------------------*/
	
	/* ---------------- Start Call Back Enquiry Management -------------------*/
	
	public function call_back_enquiry()
    {
	   $data['enquiry']=CallBack::orderby('id','desc')->get();
	   
       return view('admin.callback.index',$data);
    }
	
	/* ---------------- End Call Back Enquiry Management -------------------*/
	
	/* ---------------- Manage Subject -------------------*/
	
	public function subject()
    {
	   $data['subject']=Subject::where('delete_status','0')->orderby('id','desc')->get();
	   
       return view('admin.subject.index',$data);
    }
    
    public function add_subject()
    {
	   $data['subject']=Subject::where('delete_status','0')->orderby('id','desc')->get();

       return view('admin.subject.add',$data);
    }
    
    public function post_add_subject(Request $request)
    {
        $validator = Validator::make($request->all(), [
	        'name'=>'required|max:2000|min:0',
	    ]);
	    
	    if($validator->fails())
	    {
	        return redirect()->back()->withErrors($validator)->withInput();
	    }
	    
	    $userprofile = new Subject;
	    
	    $userprofile->name          = $request->name;
	    $userprofile->delete_status = '0';
	    $userprofile->status        = '0';
	    $userprofile->save();
	
	    \Session::put('success','Data Added Successfully.');
	    return redirect()->route('subject');
	}
	
	public function edit_subject($id)
    {
		$data['info'] = Subject::find($id);
	    return view('admin.subject.detail',$data);
	}
	
	public function post_edit_subject(Request $request)
	{
	    $validator = Validator::make($request->all(), [
	        'name'=>'required|max:2000|min:0',
	    ]);
	    
	    if($validator->fails())
	    {
	        return redirect()->back()->withErrors($validator)->withInput();
	        
	    }
	    $id = $request->id;
	    
	 
	    $userprofile       = Subject::find($id);
	    $userprofile->name = $request->name;
	    $userprofile->save();
	    \Session::put('success','Data Updated Successfully.');
	    return redirect("edit-subject/$id");
	}
	
	public function delete_subject($id)
    {
		
	$userprofile=Subject::find($id);

	$userprofile->delete_status='1';

	$userprofile->save();
	\Session::put('success','Data Deletes Successfully.');
	return redirect()->route('subject');
		
	}
	
	/* ---------------- End Manage Subject -------------------*/
	
	/* ---------------- Start Call Back Enquiry Management -------------------*/
	
	public function raise_ticket()
    {
	   $data['ticket']=RaiseTicket::orderby('id','desc')->get();
	   
       return view('admin.ticket.index',$data);
    }
    
    public function updateTicketStatus(Request $request) {
        $ticketId = $request->input('ticket_id');
        $newStatus = $request->input('new_status');
        
        $ticket = RaiseTicket::findOrFail($ticketId);
        $ticket->isResolved = $newStatus;
        $ticket->save();
        
        return response()->json(['success' => true]);
    }
	
	/* ---------------- End Call Back Enquiry Management -------------------*/
	

	public function logout()
	{
	Auth::logout();
	Session::flush();
	return redirect(url('admin-login'));
	}

    /* ---------------- Start Admin Settings -------------------*/
	public function Adminsetting(){
		$data['adminsetting']=Adminsettings::where('delete_status','0')->orderby('id','desc')->get();
		return view('admin.adminsetting.admin-setting',$data);
	}

	public function Addadminsetting(){
		$data['adminsettings']=Adminsettings::where('delete_status','0')->orderby('id','desc')->get();

		return view('admin.adminsetting.add-admin-settings',$data);
	}
	
	public function mis_report(){
	   $data['customer'] = Customer::with(['subscriptionhistory' => function ($query) {
            $query->orderByDesc('created_at');
        }, 'subscriptionhistory.subscriptions','countries','states','customerparent','customerchild','commission'])
        ->get();
	    return view('admin.mis-reports.index',$data);
	}
		public function referral_link_report(){
	   $data['customer'] = Customer::with(['subscriptionhistory' => function ($query) {
            $query->orderByDesc('created_at');
        }, 'subscriptionhistory.subscriptions','countries','states','customerparent','customerchild','customerallchild','commission'])
        ->get();
	    return view('admin.mis-reports.report',$data);
	}

	public function postadminsetting(Request $request){	
			$userprofile=new Adminsettings;
			$userprofile->with_in_pan    =     $request->with_in_pan;
			$userprofile->with_out_pan   =     $request->with_out_pan;
			$userprofile->admin_charges  =     $request->admin_charges;
			$userprofile->other_charges  =     $request->other_charges;
			$userprofile->reserve_member_expiry  =  $request->reserve_member_expiry;
			$userprofile->igst = $request->igst;
			$userprofile->cgst = $request->cgst;
			$userprofile->sgst = $request->sgst;
			$userprofile->company_name = $request->company_name;
			$userprofile->gstno = $request->gstno;
			$userprofile->full_address = $request->full_address;
			$userprofile->email_id = $request->email_id;
			$userprofile->contact_no = $request->contact_no;
			$userprofile->prefix_number = $request->prefix_number;
			$userprofile->serial_number = $request->serial_number;
			$userprofile->referal_join = $request->referal_join;
			$userprofile->auto_join = $request->auto_join;
			$userprofile->numer_of_view = $request->with_out_pan;
			$userprofile->delete_status = '0';
			$userprofile->status = '0';
			$userprofile->save();
			
			\Session::put('success','Data Added Successfully.');
			return redirect()->route('admin-setting');
	}


    public function temporaryDeleteCommission(){
        $data['managecommission']=Subscription::where('delete_status','0')->orderby('id','desc')->get();
		$data['managecommissions']=managecommission::where('delete_status','1')->orderby('id','desc')->get();
		return view('admin.managecommission.temporary',compact('data'));
    }
    
	public function delete_admin_setting($id)
    {
		
    	$userprofile=Adminsettings::find($id);
    
    	$userprofile->delete_status='1';
    
    	$userprofile->save();
    	\Session::put('success','Data Deletes Successfully.');
    	return redirect()->route('blog');
		
	}
	
	public function delete_manage_comission_setting($id)
    {
		
    	$userprofile=managecommission::find($id);
    
    	$userprofile->delete_status='1';
    
    	$userprofile->save();
    	\Session::put('success','Data Deletes Successfully.');
    	return redirect()->route('manage-commission-setting');
		
	}
	
	public function restore_manage_comission_setting($id)
    {
		
    	$userprofile=managecommission::find($id);
    
    	$userprofile->delete_status='0';
    
    	$userprofile->save();
    	\Session::put('success','Data Restored Successfully.');
    	return redirect()->route('manage-commission-setting');
		
	}
	
	public function permanent_delete_manage_comission_setting($id){
	    $userprofile=managecommission::find($id);
    	$userprofile->delete();
    	\Session::put('success','Data Deleted Permanently Successfully.');
    	return redirect()->route('manage-commission-setting');
	}
	


	public function edit_admin_setting($id)
    {
		$data['info'] = Adminsettings::find($id);
	    return view('admin.adminsetting.detail',$data);
	}
	
	
	
	function post_edit_admin_setting(Request $request)
	{
	
	 $userprofile=Adminsettings::first();
	 
	 $id = $request->id;
	 $userprofile->with_in_pan    =     $request->with_in_pan;
	 $userprofile->with_out_pan   =     $request->with_out_pan;
	 $userprofile->admin_charges  =     $request->admin_charges;
	 $userprofile->other_charges  =     $request->other_charges;
	 $userprofile->reserve_member_expiry  =  $request->reserve_member_expiry;
	 if($request->auto_join==1&&$userprofile->auto_join_end_date){
	   $userprofile->auto_join_start_date = date('Y-m-d');
	   $userprofile->auto_join_end_date = null;
	 }else if($request->auto_join==0){
	     $userprofile->auto_join_end_date = date('Y-m-d');
	     $userprofile->auto_join_start_date = null;
	 }
	 $userprofile->igst = $request->igst;
	 $userprofile->cgst = $request->cgst;
	 $userprofile->sgst = $request->sgst;
	 $userprofile->welcome_amount = $request->welcome_amount;
	 $userprofile->wallet_limit   = $request->wallet_limit;
	 $userprofile->company_name = $request->company_name;
	 $userprofile->gstno = $request->gstno;
	 $userprofile->full_address = $request->full_address;
	 $userprofile->email_id = $request->email_id;
	 $userprofile->contact_no = $request->contact_no;
	 $userprofile->prefix_number = $request->prefix_number;
	 $userprofile->serial_number = $request->serial_number;
	 $userprofile->referal_join = $request->referal_join;
	 $userprofile->auto_join = $request->auto_join;
	 $userprofile->is_active_ad_referral = $request->is_active_ad_referral;
	 $userprofile->numer_of_view = $request->numer_of_view;
	 $userprofile->apply_to = $request->apply_to;
	 $userprofile->country = $request->country;
	 $userprofile->state = $request->state;
	 $userprofile->city = $request->city;
	 $userprofile->apply_to_reserve_expiry_timeline = $request->apply_to_reserve_expiry_timeline;
	 $userprofile->reserve_expiry_timeline  = $request->reserve_expiry_timeline;
	 $userprofile->delete_status = '0';
	 $userprofile->status = '0';
    
    $comissions = managecommission::where('delete_status','0')->orderby('id','desc')->get();
    foreach($comissions as $comission){
        if($request->auto_join == 0){
            $comission->update(['auto_join'=>$request->auto_join]);
        }else{
            $comission->update(['auto_join'=>$comission->auto_join_save_status]);
        }
        
    }
   
	 
	$userprofile->save();
	if($request->apply_to == "all_users"){
	    if($request->old_reserve_member_expiry != $request->reserve_member_expiry){
	        $customers = Customer::all();
	    foreach ($customers as $customer) {
    $expiryDate = date('Y-m-d', strtotime($customer->datetime . ' + '.$request->reserve_member_expiry.' days'));
    $customer->update(['membership_expiry_at' => $expiryDate]);
        }
	    }
	    
	}
	
	if($request->apply_to_reserve_expiry_timeline == "all_users"){
	    if($request->reserve_expiry_timeline != $request->old_reserve_expiry_timeline){
	        $customers = Customer::whereNotNull('parent_id')->whereNull('referralto')->whereNotNull('reserve_expiry_at')->get();
	        $customer_childs = Customer_child::whereNotNull('child_id')->whereNotNull('user_id')->whereNull('removal_date')->get();
	        foreach($customer_childs as $child){
	            $days1 = (int)$request->reserve_expiry_timeline - (int)$request->old_reserve_expiry_timeline;
                $expiryDate1 = date('Y-m-d', strtotime($child->reserve_expiry_at . $days1 . ' days'));
                $child->update(['reserve_expiry_at' => $expiryDate1]);
	        }
	        foreach ($customers as $customer) {
        	    $days = (int)$request->reserve_expiry_timeline - (int)$request->old_reserve_expiry_timeline;
                $expiryDate = date('Y-m-d', strtotime($customer->reserve_expiry_at . $days . ' days'));
                $customer->update(['reserve_expiry_at' => $expiryDate]);
            }
	    }
	    
	}
	
	\Session::put('success','Data Updated Successfully.');
	return redirect("edit-admin-setting/$id");
	
	}
	


	/* ---------------- End Admin Settings -------------------*/

	/* ----------------------- Star Manage commission setting -------------*/
	public function Managecommissionsetting(){
		
		$data['managecommission']=Subscription::where('delete_status','0')->orderby('id','desc')->get();
		$data['managecommissions']=managecommission::where('delete_status','0')->orderby('id','desc')->get();
		return view('admin.managecommission.manage-commission-setting',compact('data'));
	}

	public function Addcommission(){
	
		$result['categorys'] = Subscription::where('delete_status','0')->orderby('id','desc')->get();
		return view('admin.managecommission.add-manage-commission-setting',$result);
	}


	public function Addpost_manage_commission_setting(Request $request){
		//dd($request->all());
	    $manageComExist = managecommission::where('subscription_packge_id',$request->subscription_packge_id)->exists();
	    if($manageComExist){
	        \Session::put('error','Subscription Already exists...');
			return redirect()->route('manage-commission-setting');
	    }
	     $adminsetting =Adminsettings::first();
		$userprofile=new managecommission;
			$userprofile->subscription_packge_id    =     $request->subscription_packge_id;
			$userprofile->commission   =     $request->commission;
			$userprofile->auto_join  =     $adminsetting->auto_join == 1 ? $request->auto_join : 0;
			$userprofile->auto_join_save_status  =     $request->auto_join;
			$userprofile->auto_join_member  =     $request->auto_join_member;
			$userprofile->minimum_views  =  $request->commission_level_type;
			$userprofile->delete_status = '0';
			$userprofile->status = '0';
			$userprofile->commission_level_type = $request->commission_level_type;
			$userprofile->save();

			if($request->commission_level_type == 2)
			{
				
				if(!empty($request->level_name))
        		{
					for($i=0;$i<count($request->level_name);$i++){
						CommissionLevel::create([
							'level_name'=>$request->level_name[$i],
							'level_commission'=>$request->level_commission[$i],
							'status'=>$request->level_status[$i],
							'subscription_commission_id'=>$userprofile->id,
						]);
					}
				}
			}
			
			
			
			\Session::put('success','Data Added Successfully.');
			return redirect()->route('manage-commission-setting');

	}
	

	public function edit_commission($id){
		
				
		$data['info'] = Subscription::where('id',$id)->first();
		
		$data['edit_commission'] = managecommission::where('status',0)->where('id',$id)->first();
		$data['levels'] =CommissionLevel::where('subscription_commission_id', $id)->orderBy('id', 'ASC')->get();
		
		return view('admin.managecommission.edit-manage-commission-setting',compact('data'));
	}


	function post_manage_commission_setting(Request $request)
	{
		
		//dd($request->all());
		// dd($request->subscription_id);
		 $adminsetting =Adminsettings::first();
        	$userprofile =  managecommission::find($request->id);
            $userprofile->subscription_packge_id    =     $request->subscription_packge_id;
			$userprofile->commission   =     $request->commission;
			$userprofile->auto_join  =     $adminsetting->auto_join == 1 ? $request->auto_join : 0;
			$userprofile->auto_join_save_status  =     $request->auto_join;
			$userprofile->auto_join_member  =     $request->auto_join_member;
			$userprofile->minimum_views  =  $request->minimum_views;
			$userprofile->commission_level_type = $request->commission_level_type;
			$userprofile->delete_status = '0';
			$userprofile->status = '0';
			$userprofile->save();

			if($request->commission_level_type == 2)
			{
				CommissionLevel::where('subscription_commission_id', $userprofile->id)->delete();
				if(!empty($request->level_name))
        		{
					for($i=0;$i<count($request->level_name);$i++){
						CommissionLevel::create([
							'level_name'=>$request->level_name[$i],
							'level_commission'=>$request->level_commission[$i],
							'status'=>$request->level_status[$i],
							'subscription_commission_id'=>$userprofile->id,
						]);
					}
				}
			}
//	$userprofile->save();
	\Session::put('success','Data Updated Successfully.');
	// return redirect()->route('edit-manage-commission-setting'.'/'.$id);
return redirect()->route('manage-commission-setting');
	
	}
	

	/* ----------------------- End Manage commission setting -------------*/

	
	public function WaitingSubscribersIndex(){
		$data['users'] = Customer::whereNull('reserve_expiry_at')
						->whereNull('parent_id')// Filters customers who have no related records in customer_child
                        ->get();
		$data['subscriptions'] = Subscription::all();
		return view('admin.user.view-waiting-user',$data);
	}
	public function ViewSubscriptions($id){
		$data['info'] = SubscriptionHistory::where('user_id',$id)->get();
		return view('admin.user.view-subscriptions',$data);
	}
	public function ViewSubscriptionsdetail($id){
		$data['info'] = SubscriptionHistory::where('id',$id)->first();
		return view('admin.user.view-subscriptions-detail',$data);
	}
	
	public function ViewUserSeedInfo(){
	    $data['customers'] = SubscriptionHistory::with('customers')
                            ->orderByRaw("CASE WHEN type = 'Premium' THEN 1 WHEN type = 'Prime' THEN 2 ELSE 3 END")
                            ->where('auto_join', 1)
                            ->whereDate('subscription_expiry', '>', Carbon::now())
                            ->where('join_complete', 'no')
                            ->orderBy('created_at', 'ASC')
                            ->get();
	    return view('admin.user.user-seeds',$data);
	}
	
	public function unblock_user($id){
	    $blockEnquiries = Adsenquiry::where('user_id',$id)->where('isBlocked',1)->get();
	    foreach($blockEnquiries as $data){
	        $data->isBlocked = 0;
	        $data->save();
	    }
	    $blockUser = BlockUser::where('user_id',$id)->first();
	    $blockUser->count = '0';
	    $blockUser->save();
	    
	    return redirect()->route('user')->with('success', 'User unblocked successfully.');
	}
	
	public function SubAdmin(){
	    $data['users'] = User::get();
        return view('admin.adminsetting.sub-admin',$data);
    }
    
    public function SubAdminEdit($id){
        $data['user'] = User::with('permission')->findOrFail($id);
        return view('admin.adminsetting.edit-sub-admin',$data);
        
    }
    
    public function SubAdminDelete($id)
    {
        $user = User::with('permission')->findOrFail($id);
        $user->delete(); // Delete the user record
    
        // Delete the associated sub-admin permission record
        if ($user->permission) {
            $permission = SubAdminPermission::findOrFail($user->permission->id);
            $permission->delete();
        }
    
        return redirect()->route('sub-admin')->with('success', 'Sub admin deleted successfully.');
    }
    
    public function SubAdminUpdate(Request $request){
        
        $user = User::with('permission')->findOrFail($request->id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->role_id = $request->role_id ?? '';
        if(isset($request->password)){
            $user->password = Hash::make($request->password);
        }
        $user->save();
        
        // Update the sub-admin permission record
        $subAdminPermission = SubAdminPermission::findOrFail($user->permission->id);
        $subAdminPermission->user_id = $user->id;
        $subAdminPermission->master_edit = $request->has('masterEdit') ? true : false;
        $subAdminPermission->users_edit = $request->has('userEdit') ? true : false;
        $subAdminPermission->chat_edit = $request->has('chatEdit') ? true : false;
        $subAdminPermission->invoice_order_edit = $request->has('invoiceEdit') ? true : false;
        $subAdminPermission->subscription_edit = $request->has('subscriptionEdit') ? true : false;
        $subAdminPermission->ads_edit = $request->has('adsEdit') ? true : false;
        $subAdminPermission->content_edit = $request->has('contentEdit') ? true : false;
        $subAdminPermission->help_edit = $request->has('helpEdit') ? true : false;
        $subAdminPermission->wallet_payouts_edit = $request->has('walletEdit') ? true : false;
        $subAdminPermission->mis_report_edit = $request->has('misEdit') ? true : false;
        // Update other fields here if needed
        $subAdminPermission->save();
        return redirect()->route('sub-admin')->with('success','Sub Admin Updated successfully..');
    }
    public function SubAdminCreate(Request $request){
        // Create a new user record
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'mobile' => 'required|string|max:15',
            'password' => 'required|string|min:8',
            // Add more validation rules for other fields if needed
        ]);
        if ($request->hasFile('profilePic')) {
            $profilePicPath = $request->file('profilePic')->store('uploads/admin', 'public');
        } else {
            $profilePicPath = ''; // Set a default value if no file is uploaded
        }
        
        // Create the user with the uploaded profile picture path
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'mobile' => $validatedData['mobile'],
            'password' => Hash::make($validatedData['password']),
            'role_id' => $request->role ?? '',
            'profile_pic' => $profilePicPath, // Save the profile picture path
        ]);
    
        // Create a new sub-admin permission record
        $subAdminPermission = SubAdminPermission::create([
            'user_id' => $user->id,
            'master_edit' => $request->has('masterEdit') ? true : false,
            'users_edit' => $request->has('userEdit') ? true : false,
            'chat_edit' => $request->has('chatEdit') ? true : false,
            'invoice_order_edit' => $request->has('invoiceEdit') ? true : false,
            'subscription_edit' => $request->has('subscriptionEdit') ? true : false,
            'ads_edit' => $request->has('adsEdit') ? true : false,
            'content_edit' => $request->has('contentEdit') ? true : false,
            'help_edit' => $request->has('helpEdit') ? true : false,
            'wallet_payouts_edit' => $request->has('walletEdit') ? true : false,
            'mis_report_edit' => $request->has('misEdit') ? true : false,
            // Add other fields here if needed
        ]);
        return redirect()->route('sub-admin')->with('success', 'Sub admin created successfully.');
    }

	

	public function ViewAllReferrals(){
	$data['info'] = Customer::whereNotNull('referralto')->get();
		return view('admin.user.view-my-referrals',$data);
		// $data['info'] = Customer::find($id);
// 		return view('admin.user.view-all-referrals');
	}

	public function ViewmyReferrals($id){

		$data['info'] = Customer::where('parent_id',$id)->whereNotNull('referralto')->get();
		return view('admin.user.view-my-referrals',$data);
	}
	public function ViewAutoJoiningMembers($id){

	    $data['info'] = Customer::where('parent_id',$id)->whereNull('referralto')->get();
		return view('admin.user.view-auto-joining-members',$data);
	}
	
	public function ViewAllAutoJoining(){

	    $data['info'] = Customer::whereNull('referralto')->whereNotNull('parent_id')->get();
		return view('admin.user.view-auto-joining-members',$data);
	}
	
	public function ViewAutoJoiningMembersbysubscriptions($id){

	    $data['info'] = Customer_child::where('subscription_id',$id)->get();
		return view('admin.user.view-auto-joining-members-by-subscriptions',$data);
	}

	public function Earnings(){

		$data['info'] = SubscriptionHistory::where('comission_paid_parent_id','!=',null)->get();
		return view('admin.user.earnings',$data);
	}

	public function EarningsUser($id){
		$data['info'] = SubscriptionHistory::where('comission_paid_parent_id',$id)->get();
		return view('admin.user.earnings',$data);
	}

	public function UserWallet(){

		$data['customers'] = Customer::get();
		return view('admin.wallet.user-wallet',$data);
	}

	public function WalletHistory($id){
		$data['transactions'] = WalletAmout::with('customer')->where('userid',$id)->get();
		return view('admin.wallet.wallet-history',$data);
	}
	
	public function commission_export($date)
    {
        $monthYear = $date;

        // Creating an instance of the export model and passing month and year
        $export = new CommissionExport($monthYear);

        // Triggering the export
        return Excel::download($export, 'customer_commission.xlsx');
    }
    
    public function commission_import(Request $request)
    {
        // Check if a file was uploaded
        if ($request->hasFile('importFile')) {
            try {
                Excel::import(new CommissionImport, $request->file('importFile'));
                return response()->json(['success' => true, 'message' => 'Data imported successfully']);
            } catch (\Exception $e) {
                // Provide an error message if an exception occurs during import
                return response()->json(['success' => false, 'message' => 'Error importing data: ' . $e->getMessage()]);
            }
        } else {
            // If no file was uploaded, provide an error message
            return redirect()->back()->with('error', 'No file selected for import.');
        }
    }

	public function Payouts(){
		$data['info'] = SubscriptionHistory::with("customers")
			->whereDate('subscription_expiry', '>=', now())
			->get();

		// Sum of commission-related amounts for each user
		$data['commission_sum'] = SubscriptionHistory::whereDate('subscription_expiry', '>=', now())
				->whereYear('created_at', now()->year)
				->whereMonth('created_at', now()->month)
				->groupBy('user_id')
				->selectRaw('user_id, SUM(comission_paid_amount + tds_amount_of_comission + admin_charges_of_comission + other_charges_of_comission) as total_commission')
				->selectRaw('user_id, SUM(tds_amount_of_comission) as total_tds_amount')
				->selectRaw('user_id, SUM(admin_charges_of_comission) as total_admin_charges')
				->selectRaw('user_id, SUM(other_charges_of_comission) as total_other_charges')
				->selectRaw('user_id, SUM(comission_paid_amount) as earnings')
				->get();
		$oneMonthAgo = Carbon::now()->subMonth();

		// Retrieve CustomerCommission records where created_at is one month old
		$data['pending_commission'] = CustomerCommission::whereYear('created_at', now()->year)
		->whereMonth('created_at', now()->month)
		->select(
			'id',
			'user_id',
        	'status',
			'created_at',
			DB::raw('IFNULL(parent_id, 1) AS parent_id'), // Set parent_id to 0 if it's null
			DB::raw('SUM(total_commission) as total_commission'),
			DB::raw('SUM(tds) as total_tds'),
			DB::raw('SUM(admin_charges) as total_admin_charges'),
			DB::raw('SUM(other_charges) as total_other_charges'),
			DB::raw('SUM(total_earned) as total_earned')
		)
		->where('status','pending')
		->groupBy('parent_id','user_id','status','created_at','id')
		->get();

		$data['approved_commission'] = CustomerCommission::whereYear('created_at', now()->year)
		->whereMonth('created_at', now()->month)
		->select(
			'id',
			'user_id',
        	'status',
			'created_at',
			'transaction_id',
			'payment_date',
			'payment_method',
			'reason',
			DB::raw('IFNULL(parent_id, 0) AS parent_id'), // Set parent_id to 0 if it's null
			DB::raw('SUM(total_commission) as total_commission'),
			DB::raw('SUM(tds) as total_tds'),
			DB::raw('SUM(admin_charges) as total_admin_charges'),
			DB::raw('SUM(other_charges) as total_other_charges'),
			DB::raw('SUM(total_earned) as total_earned')
		)
		->where('status','approved')
		->groupBy('parent_id','user_id','status','created_at','id','transaction_id','payment_method','payment_date','reason')
		->get();

		//dd($data['commission_sum']);
		return view('admin.wallet.payouts',$data);
	}


	public function sendEmail()
	{

		$childcust   = PrimeUser::get();

        foreach ($childcust as $data) {
            $child = new Customer_child();
            $child->user_id  = $data->id;
            $child->save();
			dispatch(new CustomerChildQ($data));
			dd('Email Send Successfully.');
        }
	
		
	
		
		

	}
	
	public function get_subscription_data($id)
    {
        
        $history        = DB::table('subscription_history')->where('id',$id)->where('delete_status',0)->orderby('id','desc')->get();
        $logo_path      = public_path('invoice/logo.svg');
        $logo_content   = file_get_contents($logo_path,false);
        $logo_64        = 'data:image/svg;base64,'.base64_encode($logo_content);
        $gstsetting        = Adminsettings::first();
        
        $subscriptionOrder = SubscriptionHistory::find($id); 
        $user_detail    = Customer::find($subscriptionOrder->user_id);
        $subscription       = Subscription::find($subscriptionOrder->subscription_id);
        $category        = Categories::whereIn('id',explode(",",$subscriptionOrder->category_id))->pluck('name');
        
        $data = array(
                'history'           => $history,
                'gstsetting'           => $gstsetting,
                'user_detail'       => $user_detail,
                'logo_64'           => $logo_64,
                'subscriptionOrder' => $subscriptionOrder,
                'subscription'     => $subscription,
                'category'        => $category
        );
        
        $pdf = PDF::loadView('website.invoice.subscription',$data);
        return $pdf->download($subscription->package.'.pdf');
       
    }
	public function poolWalletHistory($id=null){
		if(isset($id) && $id !=''){
			$data['transactions'] = LevelTransaction::with('fromUser', 'toUser', 'subscription')->where('to_member', $id)->get();
		}
		else{
			$data['transactions'] = LevelTransaction::with('fromUser', 'toUser', 'subscription')->get();
		}
		
		return view('admin.wallet.pool-wallet-history',$data);
	}
	public function poolWalletSummery(){
		$data['transactions'] = LevelTransaction::join('customers', 'customers.id', '=', 'level_transactions.to_member')
											->select('level_transactions.id as ID','level_transactions.status',DB::raw('sum(actual_amount) as `amount`'), DB::raw('sum(deduction) as `total_deduction`'), DB::raw('sum(commission_amount) as `total_amount`'), DB::raw("CONCAT_WS('-',MONTH(level_transactions.created_at),YEAR(level_transactions.created_at)) as monthyear"), 'customers.name', 'customers.mobile', 'customers.id as CID')
											->groupBy('to_member', 'monthyear')
											->get();
			
		return view('admin.wallet.pool-wallet-summery',$data);
	}
	
	
	public function userPayouts(Request $request)
    {
       
		$data['commission'] = CustomerCommission::with('customerp', 'customer', 'subscription')->whereYear('created_at', now()->year)
			->whereMonth('created_at', now()->month)
			->select(
				'id',
				'status',
				'created_at','reason','image','payment_method','level_transaction_id','user_id', 'subscription_id',
				DB::raw('IFNULL(parent_id, user_id) AS parent_id'), // Set parent_id to 0 if it's null
				DB::raw('SUM(total_commission) as total_commission'),
				DB::raw('SUM(tds) as total_tds'),
				DB::raw('SUM(admin_charges) as total_admin_charges'),
				DB::raw('SUM(other_charges) as total_other_charges'),
				DB::raw('SUM(total_earned) as total_earned')
			)
			->groupBy('parent_id','user_id','status','created_at','id','reason','image','payment_method')
			->get();
			
        return view('admin.mis-reports.payouts',$data);
    }
    public function defaultNotificationsHistory()
    {
       
		$data['notifications'] = DefaultNotificationHistory::with('customer')->get();
			
        return view('admin.notification.notifications-history',$data);
    }
    public function defaultNotifications()
    {
       
		$data['notifications'] = DefaultNotification::get();
			
        return view('admin.notification.notification-events',$data);
    }
    
    public function customNotifications()
    {
       
		$data['notifications'] = CustomNotificationHistory::with('customer')->get();
			
        return view('admin.notification.custom-notifications-history',$data);
    }
    public function updateNotificationContents(Request $request){
		$request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $notification = DefaultNotification::findOrFail($request->id);
		
		
		
		$notification->title = $request->title;
		$notification->content = $request->content;
		$notification->save();
        return redirect()->route('manage-default-notifications')->with('success', 'Message updated successfully.');
	}

    public function updateinfocardstatus($id)
    {
		
		
    	$card=InfoCard::find($id);
         if(!empty($card))
         {
             $status = $card->status == 'Active' ? 'Inactive': 'Active';
             $card->status = $status;
             $card->save();
         }
    	
    	
    	\Session::put('success','Status Updated Successfully.');
    	return redirect()->route('infocard.index');
		
	}
	
}
