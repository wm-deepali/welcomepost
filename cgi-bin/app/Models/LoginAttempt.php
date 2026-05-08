<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginAttempt extends Model
{
    use HasFactory;

    protected $table = "login_attempts";
    protected $fillable = ['user_id', 'last_login', 'attempt_count', 'is_account_locked'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
