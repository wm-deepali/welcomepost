<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\WalletAmout;
use App\Models\Adminsettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use App\Models\LoginAttempt;

class GoogleController extends Controller
{
    public function redirectToGoogle(Request $request)
    {
        $referralCode = $request->query('referralCode');
        
        if ($referralCode) {
            $userRefName = Customer::where('referral_code',$referralCode)->first();
            session(['referralCode' => $referralCode,'refUserName' => $userRefName->name]);
        }

        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
         
        $user = Socialite::driver('google')->stateless()->user();
        
       
        $existingUser = Customer::withTrashed()->where('email', $user->getEmail())->first();
        if ($existingUser) 
        {
            $loginAttempt = LoginAttempt::where('user_id',$existingUser->id)->first();
		    if (isset($loginAttempt)&&$loginAttempt->is_account_locked) {
                \Session::put('loginAttepmt','1');
		        return redirect(url('login'));
            }
            if($existingUser->delete_status==1&&isset($existingUser->deleted_at)){
                \Session::put('error','Your account has been deleted, you can contact us for restoration of it with in 30 Days.');
		        return redirect(url('login'));
            }
            if($existingUser->status==0){
                $stu_id         = $existingUser->id;
                $request->session()->put('id',$stu_id);
                if (session()->has('id')) {
                    if ($existingUser->wallet_bonus >= 10) {
                        session()->put('welcomeAmount', $existingUser->wallet_bonus);
                    }
                    if(isset($loginAttempt)){
                        $loginAttempt->attempt_count = 0;
                        $loginAttempt->last_login = now();
                        $loginAttempt->save();
                    }
                }
                if($existingUser && is_null($existingUser->mobile)){
                    $request->session()->flash('error','Please complete the sign up...');
                    return redirect()->route('first.details');
                }
        		return redirect('/');
            }else{
                \Session::put('error','Your Account has been De-activated.');
				return redirect(url('login'));
            }
    				
            //Auth::login($existingUser, true);

        } else {
            $adminsetting=Adminsettings::first();
            //$referral_code = str_pad($referral_code, 4, '0', STR_PAD_LEFT);
            $namePart = substr($user->getName(), 0, 4);
    		$mobilePart = rand(1000,9999);
		    $user_id = $namePart . $mobilePart;
             $newUser = Customer::create([
                 'name'      => $user->getName(),
                 'email'     => $user->getEmail(),
                 'google_id' => $user->getId(),
                 'referral_code' => $user_id,
                 'image' => $user->getAvatar(),
                 'is_email_verified' => '1',
                 'membership_expiry_at' => date('Y-m-d', strtotime(date('d-m-Y H:i:s') . ' + '.$adminsetting->reserve_member_expiry.' days')),
		          'wallet_bonus' 	=> $adminsetting->welcome_amount,
                 'datetime' => date('Y-m-d H:i:s'),
                 'delete_status' => '0',
                 'status' => '0',
                 'no_of_ads' => '0',
                 'is_email_verified' => '1',
                 'member_id'	=> 'WP'.date('Y').rand(1000,9999),
                 'user_type' => 'Free'
                 

                ]);
                if ($adminsetting->welcome_amount > 0) {
                    $walletamout = new WalletAmout();
        			$walletamout->amount = $newUser->wallet_bonus;
        			$walletamout->userid = $newUser->id;
        			$walletamout->status = "1";
        			$walletamout->datetime = date("d/m/y/ h:i:s A");
        			$walletamout->description = "₹".$newUser->wallet_bonus." credited to your wallet for welcome bonus";
        			$walletamout->save();
                    $welcomeAmount = $adminsetting->welcome_amount;
                    $request->session()->put('id',$newUser->id);
                    return redirect()->route('first.details');
                }
                $request->session()->put('id',$newUser->id);
    		    return redirect()->route('first.details');
        }
    }
}