<?php
  
namespace App\Models;
  
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
  
class DefaultNotificationHistory extends Model
{
    use HasFactory;
  
    public $table = "default_notification_history";
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    protected $fillable = [
        'customer_id',
        'event',
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