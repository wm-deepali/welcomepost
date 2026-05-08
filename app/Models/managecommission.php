<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class managecommission extends Model
{
    use HasFactory;
    protected $table='manage_commission';
    protected $fillable=[
        'subscription_id','subscription_packge_id','commission','auto_join','auto_join_member','minimum_views','delete_status','status','auto_join_save_status','commission_level_type'
        ];
}
