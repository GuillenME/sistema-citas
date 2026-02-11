<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeSetting extends Model
{
    use HasFactory;
    protected $table = 'home_settings';

    protected $fillable = [
        'hero_title',
        'hero_subtitle',
        'feature_1_title',
        'feature_1_description',
        'feature_2_title',
        'feature_2_description',
        'feature_3_title',
        'feature_3_description',
        'hero_image',
        'navbar_logo',
        'footer_address',
        'footer_phone',
        'footer_hours',
    ];


}
