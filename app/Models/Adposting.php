<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adposting extends Model
{
    use HasFactory;
	protected $table='ads_postings';
	
	public function subscriptionhistory(){
        return $this->hasOne(SubscriptionHistory::class,'id','subscription_id');
        
    }
    public function category(){
        return $this->belongsTo(Categories::class,'category_id','id');
        
    }
    public function subcategory(){
        return $this->belongsTo(Subcategories::class,'sub_category_id','id');
        
    }
    public function ad_city(){
        return $this->belongsTo(City::class,'city','id');
        
    }
}