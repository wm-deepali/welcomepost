<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class WalletAmout extends Model
{
    use HasFactory;
	protected $table='walletamout';

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'userid');
    }
    
    public function scopeCurrentMonthWallet($query)
    {
        return $query->where('description', 'LIKE', '%Commission%')
                     ->whereMonth('created_at', Carbon::now()->month)
                     ->whereYear('created_at', Carbon::now()->year);
    }
    
    public function scopeAllWallet($query)
    {
        return $query->where('description', 'LIKE', '%Commission%');
    }
    
    public function scopeCashfree($query)
    {
        return $query->where('description', 'LIKE', '%cashfree%');
    }
    
    public function scopeWelcomeBonus($query)
    {
        return $query->where('description', 'LIKE', '%Welcome%');
    }
    
    public function scopeCurrentMonthCashfree($query)
    {
        return $query->where('description', 'LIKE', '%cashfree%')
                     ->whereMonth('created_at', Carbon::now()->month)
                     ->whereYear('created_at', Carbon::now()->year);
    }
}
