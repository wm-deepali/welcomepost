<?php
  
namespace App\Models;
  
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
  
class DefaultNotification extends Model
{
    use HasFactory;
  
    public $table = "default_notifications";
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    protected $fillable = [
        'event',
        'title',
        'content'
    ];
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    
}