<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adsenquiry extends Model
{
    use HasFactory;
	protected $table='ads_enquiries';
	protected $fillable = [
        'name',
        'email',
        'mobile',
        'message',
        'post_id',
        'user_id',
        'receiver_id',
        'status',
        'reply',
        'isBlocked',
        'block_reason',
        
    ];
	
}