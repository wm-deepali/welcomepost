<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RazorpaySetting extends Model
{
    use HasFactory;
	protected $table='razorpay_settings';
	protected $fillable = ['key_id','secret_id',];
}