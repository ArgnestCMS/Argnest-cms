<?php

namespace App\Filament\Resources\SiteSettings\Pages;

use App\Filament\Resources\SiteSettings\SiteSettingResource;
use App\Models\AdminActivityLog;
use App\Services\AdminActivityLogger;
use Filament\Resources\Pages\EditRecord;

class EditSiteSetting extends EditRecord
{
    protected static string $resource = SiteSettingResource::class;

    protected function afterSave(): void
    {
        $changes = array_keys($this->record->getChanges());
        $mailFields = [
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
        ];

        $action = count(array_intersect($changes, $mailFields)) > 0
            ? AdminActivityLog::ACTION_MAIL_SETTINGS_UPDATED
            : AdminActivityLog::ACTION_SITE_SETTINGS_UPDATED;

        app(AdminActivityLogger::class)->log(
            $action,
            'Site ayarlari duzenlendi: ' . ($this->record->site_name ?: 'Ayar kaydi #' . $this->record->id),
        );
    }
}
