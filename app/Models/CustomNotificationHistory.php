<?php
  
namespace App\Models;
  
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
  
class CustomNotificationHistory extends Model
{
    use HasFactory;
  
    public $table = "custom_notification_history";
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    protected $fillable = [
        'customer_id',
        'image',
        'title',
        'message',
        'status',
        'read_at'
    ];
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}