<?php

namespace App\Filament\Resources\Roles\Pages;

use App\Filament\Resources\Roles\RoleResource;
use App\Models\AdminActivityLog;
use App\Services\AdminActivityLogger;
use Filament\Resources\Pages\CreateRecord;

class CreateRole extends CreateRecord
{
    protected static string $resource = RoleResource::class;

    protected function afterCreate(): void
    {
        app(AdminActivityLogger::class)->log(
            AdminActivityLog::ACTION_ROLE_CREATED,
            'Rol olusturuldu: ' . $this->record->name,
        );
    }
}
