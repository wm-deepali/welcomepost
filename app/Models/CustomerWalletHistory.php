<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 
class CustomerWalletHistory extends Model
{
    use HasFactory;
	protected $table='customer_wallet_histories';
	 protected $fillable = ['customer_id','subscription_id','child_id','amount','wallet_amount_before_transaction','wallet_amount_after_transaction','transaction_type','payment_method','status','transaction_date',
	 'tds_amount','admin_charges','other_charges'];

    public function customer(){
        return $this->belongsTo(Customer::class,'customer_id','id');
    }
    public function customerchild(){
        return $this->belongsTo(Customer::class,'child_id','id');
    }
    
    public function subscriptionhistory(){
        return $this->hasMany(SubscriptionHistory::class,'subscription_id','id');
        
    }
    
    
    
    
}