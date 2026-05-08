<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    use HasFactory;
    protected $fillable=[
            'enquiry_name',
            'enquiry_email',
            'enquiry_mobile',
            'enquiry_category',
            'enquiry_message',
            

    ];
}
