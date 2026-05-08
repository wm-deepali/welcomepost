<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrimeUser extends Model
{
    use HasFactory;

    protected $table='prime_user';

    protected $fillable = ['user_id','subscription_id','total_child_count','complete_child_count','remaining_child_count','status'];
}
