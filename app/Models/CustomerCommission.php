<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerCommission extends Model
{
    use HasFactory;
    protected $table = 'customer_commission';

    protected $fillable = [
        'user_id',
        'parent_id',
        'subscription_id',
        'total_commission',
        'tds',
        'admin_charges',
        'other_charges',
        'total_earned',
        'status',
        'payment_date',
        'payment_method',
        'transaction_id',
        'reason',
        'image',
        'level_transaction_id'
    ];

    protected $casts = [
        'status' => 'string', // Casting the status field to string
    ];

    /**
     * Define a belongsTo relationship with the Customer model.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'user_id');
    }
    
    public function customerp()
    {
        return $this->belongsTo(Customer::class, 'parent_id');
    }
    
    public function subscription()
    {
        return $this->belongsTo(Subscription::class, 'subscription_id');
    }
    
    
    public function levelTrans()
    {
        return $this->belongsTo(LevelTransaction::class, 'level_transaction_id');
    }
}
