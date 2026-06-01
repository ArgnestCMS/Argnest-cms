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
        'smtp_host',
        'smtp_port',
        'smtp_username',
        'smtp_password',
        'smtp_encryption',
        'mail_from_address',
        'mail_from_name',
        'admin_notification_email',
        'support_notification_email',
        'sales_notification_email',
        'customer_email_verification_enabled',
        'live_chat_enabled',
    ];

    protected function casts(): array
    {
        return [
            'smtp_port' => 'integer',
            'smtp_password' => 'encrypted',
            'customer_email_verification_enabled' => 'boolean',
            'live_chat_enabled' => 'boolean',
        ];
    }
}
