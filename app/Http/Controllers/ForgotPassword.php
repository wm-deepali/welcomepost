<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB; 
use Carbon\Carbon; 
use App\Models\User; 
use App\Models\LoginAttempt;
use App\Models\Customer;
use Mail; 
use Hash;
use Illuminate\Support\Str;
use App\Mail\MailForgotPassword;


class ForgotPassword extends Controller
{
    public function showForgetPasswordForm()
    {
        return view('frontend.customer.forgetPassword');
    }
    
    public function submitForgetPasswordForm(Request $request)
    {
        $request->validate([
          'email' => 'required|email|exists:customers',
        ]);

        $token = Str::random(64);

        DB::table('password_resets')->insert([
            'email' => $request->email, 
            'token' => $token, 
            'created_at' => Carbon::now()
        ]);
        
        $mailData = ['token'=>$token];
        
    	$mailContent =  Mail::to($request->email )->send(new MailForgotPassword($mailData));

        return back()->with('success', 'We have e-mailed your password reset link! Please check your email in inbox, spam and junk folder.');
    }
    
    public function showResetPasswordForm($token) 
    { 
        return view('website.forgetPasswordLink', ['token' => $token]);
    }
    
    public function submitResetPasswordForm(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required'
        ]);

        $updatePassword = DB::table('password_resets')->where(['token' => $request->token])->first();

        if(!$updatePassword){
            return back()->withInput()->with('error', 'Invalid token!');
        }

        $user = Customer::where('email', $updatePassword->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();
        $loginAttempt = LoginAttempt::where('user_id',$user->id)->first();
        $loginAttempt->is_account_locked = false;
        $loginAttempt->save();
        DB::table('password_resets')->where(['token' => $request->token])->delete();

         return redirect('/login')->with('success', 'Your password has been changed!');
    }
}
