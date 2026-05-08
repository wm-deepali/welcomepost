<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashFreeSetting extends Model
{
    use HasFactory;
	protected $table='cashfree_settings';
	protected $fillable = ['key_id','secret_id',];
}
