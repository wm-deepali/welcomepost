<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LevelTransaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'subscription_id',
        'level',
        'from_member',
        'to_member',
        'commission',
        'actual_amount',
        'deduction',
        'commission_amount',
    ];

    public function fromUser(){
        return $this->belongsTo(Customer::class,'from_member', 'id');
    }

    public function toUser(){
        return $this->belongsTo(Customer::class,'to_member', 'id');
    }
    public function subscription(){
        return $this->belongsTo(Subscription::class,'subscription_id','id');
    }
    
    public function commission()
    {
        return $this->hasOne(CustomerCommission::class, 'level_transaction_id');
    }
}
