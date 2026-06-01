<?php

namespace App\Filament\Pages;

use App\Filament\Resources\AdminActivityLogs\AdminActivityLogResource;
use App\Filament\Resources\AdminUsers\AdminUserResource;
use App\Filament\Resources\CustomerServices\CustomerServiceResource;
use App\Filament\Resources\Customers\CustomerResource;
use App\Filament\Resources\LiveChatSessions\LiveChatSessionResource;
use App\Filament\Resources\SupportTickets\SupportTicketResource;
use App\Filament\Resources\SystemBackups\SystemBackupResource;
use App\Models\AdminActivityLog;
use App\Models\CustomerNotification;
use App\Models\CustomerService;
use App\Models\LiveChatSession;
use App\Models\SiteSetting;
use App\Models\SupportTicket;
use App\Models\SystemBackup;
use App\Models\User;
use Composer\InstalledVersions;
use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Support\Facades\Schema;

class Dashboard extends BaseDashboard
{
    protected string $view = 'filament.pages.dashboard';

    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?string $title = 'Argnest CRM Dashboard';

    public function getWidgets(): array
    {
        return [];
    }

    public function getViewData(): array
    {
        $user = auth()->user();
        $settings = SiteSetting::query()->first();
        $latestBackup = class_exists(SystemBackup::class)
            ? SystemBackup::query()->latest('created_at')->first()
            : null;

        return [
            'user' => $user,
            'roleName' => $this->roleName($user),
            'system' => [
                'app_version' => 'Sprint 30.3',
                'laravel_version' => app()->version(),
                'filament_version' => InstalledVersions::isInstalled('filament/filament')
                    ? InstalledVersions::getPrettyVersion('filament/filament')
                    : 'Bilinmiyor',
                'php_version' => PHP_VERSION,
                'mail_active' => filled($settings?->smtp_host) || filled(config('mail.mailers.smtp.host')),
                'live_chat_active' => (bool) $settings?->live_chat_enabled,
                'backup_active' => class_exists(SystemBackup::class) && Schema::hasTable('system_backups'),
                'installer_locked' => filter_var(env('APP_INSTALLED', false), FILTER_VALIDATE_BOOL)
                    || file_exists(storage_path('framework/argnest-installed.lock')),
            ],
            'kpis' => [
                [
                    'label' => 'Toplam Müşteri',
                    'value' => User::query()->where('role', User::ROLE_CUSTOMER)->count(),
                    'tone' => 'blue',
                ],
                [
                    'label' => 'Aktif Hizmet',
                    'value' => CustomerService::query()->where('is_active', true)->count(),
                    'tone' => 'emerald',
                ],
                [
                    'label' => 'Açık Destek Talebi',
                    'value' => SupportTicket::query()
                        ->whereIn('status', [SupportTicket::STATUS_OPEN, SupportTicket::STATUS_PENDING, SupportTicket::STATUS_ANSWERED])
                        ->count(),
                    'tone' => 'amber',
                ],
                [
                    'label' => 'Aktif Canlı Sohbet',
                    'value' => LiveChatSession::query()
                        ->whereIn('status', [LiveChatSession::STATUS_OPEN, LiveChatSession::STATUS_ANSWERED])
                        ->count(),
                    'tone' => 'violet',
                ],
                [
                    'label' => 'Toplam Yedek',
                    'value' => Schema::hasTable('system_backups') ? SystemBackup::query()->count() : 0,
                    'tone' => 'slate',
                ],
                [
                    'label' => 'Okunmamış Bildirim',
                    'value' => CustomerNotification::query()->unread()->count(),
                    'tone' => 'rose',
                ],
            ],
            'latestBackup' => $latestBackup,
            'activities' => AdminActivityLog::query()
                ->with('admin')
                ->latest('created_at')
                ->take(10)
                ->get(),
            'urls' => [
                'profile' => $user instanceof User && AdminUserResource::canEdit($user)
                    ? AdminUserResource::getUrl('edit', ['record' => $user])
                    : null,
                'security' => AdminActivityLogResource::canViewAny()
                    ? AdminActivityLogResource::getUrl('index')
                    : null,
                'backups' => SystemBackupResource::canViewAny()
                    ? SystemBackupResource::getUrl('index')
                    : null,
            ],
            'quickActions' => $this->quickActions(),
        ];
    }

    private function quickActions(): array
    {
        return collect([
            [
                'label' => 'Yeni Müşteri',
                'url' => CustomerResource::canCreate() ? CustomerResource::getUrl('create') : null,
            ],
            [
                'label' => 'Yeni Hizmet',
                'url' => CustomerServiceResource::canCreate() ? CustomerServiceResource::getUrl('create') : null,
            ],
            [
                'label' => 'Yeni Destek Talebi',
                'url' => SupportTicketResource::canCreate() ? SupportTicketResource::getUrl('create') : null,
            ],
            [
                'label' => 'Yeni Admin',
                'url' => AdminUserResource::canCreate() ? AdminUserResource::getUrl('create') : null,
            ],
            [
                'label' => 'Yeni Yedek Al',
                'url' => SystemBackupResource::canCreate() ? SystemBackupResource::getUrl('index') : null,
            ],
            [
                'label' => 'Canlı Destek',
                'url' => LiveChatSessionResource::canViewAny() ? LiveChatSessionResource::getUrl('index') : null,
            ],
        ])
            ->filter(fn (array $action): bool => filled($action['url']))
            ->values()
            ->all();
    }

    private function roleName(?User $user): string
    {
        if (! $user instanceof User) {
            return 'Misafir';
        }

        if ($user->isFirstAdmin()) {
            return 'Kurucu Yönetici';
        }

        $role = $user->roles()->orderBy('name')->value('name');

        return $role ?: ($user->role === User::ROLE_ADMIN ? 'Yönetici' : 'Kullanıcı');
    }
}
