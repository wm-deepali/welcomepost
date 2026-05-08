<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfoCard extends Model
{
    use HasFactory;
    
    protected $table = 'infocard';

    protected $fillable = [
        'title',
        'description',
        'icon',
        'url',
    ];
}
