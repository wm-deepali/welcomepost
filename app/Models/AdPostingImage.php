<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdPostingImage extends Model
{
    use HasFactory;
	protected $table='ads_posting_images';
	protected $fillable = [
        'ads_id',
        'image',
        'image_no'
        
    ];
}