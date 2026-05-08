<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer_child extends Model
{
    use HasFactory;
	protected $table='customer_child';

    protected $fillable = [
        'id', 
        'user_id',
        'subscription_id',
        'joining_date',
        'removal_date',
        'reserve_expiry_at',
        'child_id',
        'status',
    ];
    public function customerchild(){
        return $this->belongsTo(Customer::class,'child_id','id');
    }
    public function customerparent(){
        return $this->belongsTo(Customer::class,'user_id','id');
    }
    public function subscriptionhistory(){
        return $this->belongsTo(SubscriptionHistory::class,'subscription_id','id');
        
    }
    
}


