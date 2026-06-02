<?php

namespace App\Models;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

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
            'customer_email_verification_enabled' => 'boolean',
            'live_chat_enabled' => 'boolean',
        ];
    }

    protected function smtpPassword(): Attribute
    {
        return Attribute::make(
            get: function (?string $value): ?string {
                if ($value === null || $value === '') {
                    return null;
                }

                try {
                    return Crypt::decryptString($value);
                } catch (DecryptException $exception) {
                    Log::warning('Site setting SMTP password could not be decrypted.', [
                        'site_setting_id' => $this->getKey(),
                        'attribute' => 'smtp_password',
                        'exception' => $exception->getMessage(),
                    ]);

                    return null;
                }
            },
            set: fn (?string $value): ?string => filled($value) ? Crypt::encryptString($value) : null,
        );
    }
}
