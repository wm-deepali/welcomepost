<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class premium extends Model
{
    protected $table='premium';
    protected $fillable = [
        'user_id', 
        'subcription_id',
        'ad_view_count_id',
        'click',

    ]; 
    use HasFactory;
}
