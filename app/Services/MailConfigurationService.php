<?php

namespace App\Services;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Mail;

class MailConfigurationService
{
    public function apply(?SiteSetting $settings = null): void
    {
        $settings ??= SiteSetting::query()->first();

        if (! $settings) {
            return;
        }

        $this->applyFromConfig($settings);
        $this->applySmtpConfig($settings);

        Mail::forgetMailers();
    }

    private function applyFromConfig(SiteSetting $settings): void
    {
        if (filter_var($settings->mail_from_address, FILTER_VALIDATE_EMAIL)) {
            config(['mail.from.address' => $settings->mail_from_address]);
        }

        if (filled($settings->mail_from_name)) {
            config(['mail.from.name' => $settings->mail_from_name]);
        }
    }

    private function applySmtpConfig(SiteSetting $settings): void
    {
        if (blank($settings->smtp_host)) {
            return;
        }

        $smtpConfig = config('mail.mailers.smtp', []);
        $encryption = $settings->smtp_encryption;

        config([
            'mail.default' => 'smtp',
            'mail.mailers.smtp' => array_merge($smtpConfig, [
                'transport' => 'smtp',
                'scheme' => $encryption === 'ssl' ? 'smtps' : 'smtp',
                'host' => $settings->smtp_host,
                'port' => $settings->smtp_port ?: ($encryption === 'ssl' ? 465 : 587),
                'username' => $settings->smtp_username,
                'password' => $settings->smtp_password,
                'auto_tls' => $encryption !== 'none',
            ]),
        ]);
    }
}
