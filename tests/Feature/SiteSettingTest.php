<?php

namespace Tests\Feature;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class SiteSettingTest extends TestCase
{
    public function test_smtp_password_returns_null_and_logs_when_it_cannot_be_decrypted(): void
    {
        Log::spy();

        $settings = new SiteSetting();
        $settings->setRawAttributes([
            'id' => 123,
            'smtp_password' => 'encrypted-with-another-key',
        ], true);

        $this->assertNull($settings->smtp_password);

        Log::shouldHaveReceived('warning')
            ->once()
            ->with(
                'Site setting SMTP password could not be decrypted.',
                \Mockery::on(fn (array $context): bool => $context['site_setting_id'] === 123
                    && $context['attribute'] === 'smtp_password')
            );
    }

    public function test_smtp_password_is_encrypted_and_decrypted_for_valid_values(): void
    {
        $settings = new SiteSetting();
        $settings->smtp_password = 'secret-password';

        $rawPassword = $settings->getRawOriginal('smtp_password') ?? $settings->getAttributes()['smtp_password'];

        $this->assertNotSame('secret-password', $rawPassword);
        $this->assertSame('secret-password', Crypt::decryptString($rawPassword));
        $this->assertSame('secret-password', $settings->smtp_password);
    }
}
