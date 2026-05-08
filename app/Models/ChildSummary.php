<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildSummary extends Model
{
    use HasFactory;
	protected $table='child_summary';

    protected $fillable = [
        'id',
        'user_id',
        'status',
    ];
    
}
