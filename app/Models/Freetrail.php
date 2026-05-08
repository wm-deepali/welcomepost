<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Freetrail extends Model
{
    use HasFactory;
	protected $table='subscriptions_free_trials';
	protected $fillable = [
        'category_id',
        'no_of_ads',
        'ads_validity',
        'status',
        'delete_status',
    ];
}