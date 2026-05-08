<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CallBack extends Model
{
    use HasFactory;
	protected $table='call_back';
	 protected $fillable = [
        'name',
        'email',
        'mobile',
        
    ];
}