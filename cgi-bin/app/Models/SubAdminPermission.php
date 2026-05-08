<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubAdminPermission extends Model
{
    use HasFactory;
    protected $table='sub_admin_permission';
    
    protected $fillable = [
        'user_id',
        'master_edit',
        'users_edit',
        'chat_edit',
        'invoice_order_edit',
        'subscription_edit',
        'ads_edit',
        'content_edit',
        'setting_edit',
        'help_edit',
        'wallet_payouts_edit',
        'mis_report_edit',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
