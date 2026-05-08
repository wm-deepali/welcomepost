<?php
  
namespace App\Models;
  
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
  
class SubscriptionCosting extends Model
{
    use HasFactory;
  
    public $table = "subscription_costing";
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    protected $fillable = [
        'id',
        'ad_costing',
    ];
  
    
}