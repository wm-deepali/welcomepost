<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faqcategory extends Model
{
    use HasFactory;
	protected $table='faq_category';
	
	public function faqs()
    {
        return $this->hasMany(Faq::class, 'category_id', 'id');
    }
}