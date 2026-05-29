<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'site_name',
        'site_slogan',
        'site_description',
        'phone',
        'email',
        'whatsapp',
        'address',
        'google_maps_url',
        'logo',
        'favicon',
        'hero_banner',
        'hero_background',
        'facebook_url',
        'instagram_url',
        'linkedin_url',
        'youtube_url',
        'x_url',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'kvkk_text',
        'privacy_policy',
        'cookie_policy',
        'information_security_policy',
        'footer_text',
        'copyright_text',
    ];
}
