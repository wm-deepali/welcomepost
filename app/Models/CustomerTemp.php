<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 
use App\Models\BlockUser;
class CustomerTemp extends Model
{
   
    use HasFactory,SoftDeletes;
	protected $table='customers_temp';
	
	 protected $dates = ['deleted_at'];
	 protected $fillable = ['id','parent_id','name','email','password','mobile','user_type','no_of_ads','gender','dob','address','country','state','city','pin','youtube','tax_no','facebook','twitter','introduction','website','image',
	 'delete_status','delete_at','status','google_id','created_at','updated_at','is_email_verified','referral_code','referralto','membership_expiry_at','datetime','reserve_expiry_at','fcm_token','wallet_amount','member_id','wallet_bonus','qr_code_image','deleted_by'];

    public function blockUser()
    {
        return $this->hasOne(BlockUser::class, 'user_id', 'id');
    }
    
    public function adsenquiries()
    {
        return $this->hasMany(Adsenquiry::class, 'user_id', 'id');
    }
    public function otp()
    {
        return $this->hasOne(CustomerOTP::class, 'customer_id');
    }
    
    public function customerchild(){
        return $this->hasMany(Customer_child::class,'user_id','id');
    }
    public function customerallchild(){
        return $this->hasMany(Customer::class,'parent_id','id');
    }
    public function customerparent(){
        return $this->hasOne(Customer::class,'id','parent_id');
    }

    public function commission(){
        return $this->hasMany(CustomerCommission::class,'user_id');
    }
    
    public function tickets(){
        return $this->hasMany(RaiseTicket::class,'user_id');
    }
    public function walletTransactions()
    {
        return $this->hasMany(WalletAmout::class, 'userid');
    }
    public function subscriptionhistory(){
        return $this->hasMany(SubscriptionHistory::class,'user_id','id');
        
    }
    public function subscriptionorder(){
        return $this->hasMany(SubscriptionOrder::class,'user_id','id');
        
    }
    public function subscriptionhistorypaymentchild(){
        return $this->hasMany(SubscriptionHistory::class,'comission_paid_parent_id','id');
        
    }
    public function subscriptionhistorypayment(){
        return $this->hasMany(SubscriptionHistory::class,'comission_paid_parent_id','parent_id');
        
    }
    public function loginAttempt(){
        return $this->belongsTo(LoginAttempt::class,'id','user_id');
    }
    public function countries(){
        return $this->belongsTo(Countries::class,'country','id');
    }
    public function states(){
        return $this->belongsTo(States::class,'state','id');
    }
    public function cities(){
        return $this->belongsTo(City::class,'city','id');
    }
    
}
