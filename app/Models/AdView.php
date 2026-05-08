<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdView extends Model
{
    use HasFactory;

    protected $table = 'ad_views';

    protected $fillable = ['ad_id', 'user_id','session_id'];
}