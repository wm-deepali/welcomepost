<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FooterSetting extends Model
{
    use HasFactory;
    protected $table = "footer_settings";
    protected $fillable = ['title','description','url','button_text','youtube_link','facebook_link','linkedin_link','twitter_link','instagram_link'];
}
