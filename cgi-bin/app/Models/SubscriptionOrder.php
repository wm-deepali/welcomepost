<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionOrder extends Model
{
    use HasFactory;
	protected $table='subscription_orders';
	public function customer(){
        return $this->hasOne(Customer::class,'id','user_id');
    }
    
    public function subscription(){
        return $this->belongsTo(Subscription::class,'subscription_id','id');
        
    }
    
}