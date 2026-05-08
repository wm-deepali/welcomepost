<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adminsettings extends Model
{
    use HasFactory;
	protected $table='admin_setting';
    
    public function countries(){
        return $this->belongsTo(Countries::class,'country','id');
    }
    public function states(){
        return $this->belongsTo(States::class,'state','id');
    }
    public function cities(){
        return $this->belongsTo(City::class,'city','id');
    }
    
}
