<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommissionLevel extends Model
{
    use HasFactory;
    protected $table='commission_levels';

    protected $fillable = [
        'subscription_commission_id',
        'level_name',
        'level_commission',
        'status'
    ];
 
    public function commissionlevel(){
        return $this->belongsTo(managecommission::class,'subscription_commission_id','id');
    }
}
