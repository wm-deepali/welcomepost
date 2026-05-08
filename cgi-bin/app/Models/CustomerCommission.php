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
}
