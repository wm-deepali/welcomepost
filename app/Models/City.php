<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;
	protected $table='cities';
	
	public function state(){
        return $this->belongsTo(States::class,'state_id','id');
        
    }
}