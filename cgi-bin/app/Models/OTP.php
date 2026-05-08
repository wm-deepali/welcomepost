<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OTP extends Model
{
    use HasFactory;
    protected $table='customer_otp';

    protected $fillable = ['customer_id','mobile', 'otp', 'expiry'];

    protected $dates = ['expiry'];
    
    public static function verifyOTP($mobile, $otp)
    {
        $otpRecord = self::where('mobile', $mobile)
            ->where('otp', $otp)
            ->where('expiry', '>=', now())
            ->first();
        
        if ($otpRecord) {
            // OTP is valid
            $otpRecord->verified = '1';
            $otpRecord->save();

            return true;
        }

        // OTP is invalid or expired
        return false;
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
