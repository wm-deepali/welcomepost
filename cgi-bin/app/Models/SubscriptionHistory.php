<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionHistory extends Model
{
    use HasFactory;
	protected $table='subscription_history';
	protected $fillable = [
	    'user_id',
	    'subscription_id',
	    'category_id',
	    'type',
	    'transaction_id',
	    'payment_method',
	    'payment_status',
	    'used_ads',
	    'remaining_ads',
	    'total_ads',
	    'auto_join',
	    'auto_join_member',
	    'join_complete',
	    'total_joined',
	    'subscription_expiry',
	    'subscription_validity',
	    'delete_status',
	    'status',
	    'mrp',
	    'discount_amount',
	    'offered_price',
	    'gst_type',
	    'gst_amount',
	    'order_amount_with_gst',
	    'comission_paid',
	    'comission_paid_parent_id',
	    'comission_paid_amount',
	    'tds_amount_of_comission',
	    'admin_charges_of_comission',
	    'other_charges_of_comission',
	    'subscription_number',
	    'payment_mode',
	    ];
	    
	    public function customers(){
	        return $this->hasOne(Customer::class,'id','user_id');
	    }
	    public function customersparent(){
	        return $this->hasOne(Customer::class,'id','comission_paid_parent_id');
	    }
	    public function subscriptions(){
	        return $this->hasOne(Subscription::class,'id','subscription_id');
	    }
	    public function adsummary(){
	        return $this->hasMany(adsummary::class,'id','subscription_id');
	    }
}