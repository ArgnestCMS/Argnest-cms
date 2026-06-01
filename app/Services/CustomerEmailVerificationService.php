<?php

namespace App\Services;

use App\Mail\CustomerEmailVerificationMail;
use App\Models\CustomerActivityLog;
use App\Models\CustomerNotification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Throwable;

class CustomerEmailVerificationService
{
    public function send(User $customer, ?Request $request = null): bool
    {
        if ($customer->role !== User::ROLE_CUSTOMER || blank($customer->email)) {
            return false;
        }

        try {
            app(MailConfigurationService::class)->apply();

            Mail::to($customer->email)->send(new CustomerEmailVerificationMail(
                $customer,
                $this->verificationUrl($customer),
            ));

            app(CustomerActivityLogger::class)->log(
                $customer,
                CustomerActivityLog::ACTION_EMAIL_VERIFICATION_SENT,
                'E-posta dogrulama maili gonderildi.',
                $request,
            );

            return true;
        } catch (Throwable $exception) {
            Log::error('Customer email verification mail could not be sent.', [
                'user_id' => $customer->id,
                'email' => $customer->email,
                'error' => $exception->getMessage(),
            ]);

            return false;
        }
    }

    public function markVerified(User $customer, ?Request $request = null): void
    {
        $wasUnverified = $customer->email_verified_at === null;

        if ($wasUnverified) {
            $customer->forceFill([
                'email_verified_at' => now(),
            ])->save();
        }

        if (! $wasUnverified) {
            return;
        }

        app(CustomerActivityLogger::class)->log(
            $customer,
            CustomerActivityLog::ACTION_EMAIL_VERIFIED,
            'E-posta adresi dogrulandi.',
            $request,
        );

        CustomerNotification::query()->create([
            'user_id' => $customer->id,
            'title' => 'E-posta adresiniz dogrulandi',
            'message' => 'E-posta adresiniz basariyla dogrulandi.',
            'type' => 'security',
            'link' => route('frontend.customer.dashboard'),
        ]);
    }

    private function verificationUrl(User $customer): string
    {
        return URL::temporarySignedRoute(
            'frontend.customer.email.verify',
            now()->addDay(),
            [
                'id' => $customer->id,
                'hash' => sha1($customer->email),
            ],
        );
    }
}
