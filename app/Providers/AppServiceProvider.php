<?php

namespace App\Providers;

use App\Models\AdminActivityLog;
use App\Models\User;
use App\Services\AdminActivityLogger;
use App\Services\ClientIpService;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(Login::class, function (Login $event): void {
            if ($event->user instanceof User && $event->user->role === User::ROLE_ADMIN) {
                $event->user->forceFill([
                    'last_login_at' => now(),
                    'last_login_ip' => app(ClientIpService::class)->ip(request()),
                ])->save();

                app(AdminActivityLogger::class)->log(
                    AdminActivityLog::ACTION_LOGIN,
                    'Admin paneline giris yapti.',
                    $event->user,
                    request(),
                );
            }
        });

        Event::listen(Logout::class, function (Logout $event): void {
            if ($event->user instanceof User && $event->user->role === User::ROLE_ADMIN) {
                app(AdminActivityLogger::class)->log(
                    AdminActivityLog::ACTION_LOGOUT,
                    'Admin panelinden cikis yapti.',
                    $event->user,
                    request(),
                );
            }
        });
    }
}
