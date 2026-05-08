<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RaiseTicket extends Model
{
    use HasFactory;
	protected $table='raise_ticket';
	protected $fillable = ['user_id','subject','subject_query','image',];
	
	public function customer(){
        return $this->belongsTo(Customer::class,'user_id');
    }
}