<?php
  
namespace App\Models;
  
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
  
class CustomerVerify extends Model
{
    use HasFactory;
  
    public $table = "customers_verify";
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    protected $fillable = [
        'customer_id',
        'token',
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