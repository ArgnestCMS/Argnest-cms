<?php

namespace App\Filament\Resources\Permissions\Pages;

use App\Filament\Resources\Permissions\PermissionResource;
use App\Models\AdminActivityLog;
use App\Services\AdminActivityLogger;
use Filament\Resources\Pages\CreateRecord;

class CreatePermission extends CreateRecord
{
    protected static string $resource = PermissionResource::class;

    protected function afterCreate(): void
    {
        app(AdminActivityLogger::class)->log(
            AdminActivityLog::ACTION_PERMISSION_CREATED,
            'Yetki olusturuldu: ' . $this->record->name . ' (' . $this->record->key . ')',
        );
    }
}
