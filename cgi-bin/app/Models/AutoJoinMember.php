<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutoJoinMember extends Model
{
    use HasFactory;
	protected $table='auto_join_members';
	protected $fillable = [
        'parent_id',
        'child_id',
        'subscription_id',
        'joiniing_date',
        'removal_date',
        'status',
        
    ];
	
}