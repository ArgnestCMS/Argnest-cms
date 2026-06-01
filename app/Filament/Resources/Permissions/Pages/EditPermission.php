<?php

namespace App\Filament\Resources\Permissions\Pages;

use App\Filament\Resources\Permissions\PermissionResource;
use App\Models\AdminActivityLog;
use App\Services\AdminActivityLogger;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPermission extends EditRecord
{
    protected static string $resource = PermissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->after(function (): void {
                    app(AdminActivityLogger::class)->log(
                        AdminActivityLog::ACTION_PERMISSION_DELETED,
                        'Yetki silindi: ' . $this->record->name . ' (' . $this->record->key . ')',
                    );
                })
                ->label('Sil'),
        ];
    }

    protected function afterSave(): void
    {
        app(AdminActivityLogger::class)->log(
            AdminActivityLog::ACTION_PERMISSION_UPDATED,
            'Yetki duzenlendi: ' . $this->record->name . ' (' . $this->record->key . ')',
        );
    }
}
