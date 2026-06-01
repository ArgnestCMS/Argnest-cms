<?php

namespace App\Filament\Resources\Roles\Pages;

use App\Filament\Resources\Roles\RoleResource;
use App\Models\AdminActivityLog;
use App\Models\Role;
use App\Services\AdminActivityLogger;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditRole extends EditRecord
{
    protected static string $resource = RoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->label('Sil')
                ->after(function (): void {
                    app(AdminActivityLogger::class)->log(
                        AdminActivityLog::ACTION_ROLE_DELETED,
                        'Rol silindi: ' . $this->record->name,
                    );
                })
                ->before(function (DeleteAction $action): void {
                    /** @var Role $record */
                    $record = $this->record;

                    if (! $record->is_system) {
                        return;
                    }

                    Notification::make()
                        ->title('Sistem rolleri silinemez.')
                        ->danger()
                        ->send();

                    $action->cancel();
                }),
        ];
    }

    protected function afterSave(): void
    {
        app(AdminActivityLogger::class)->log(
            AdminActivityLog::ACTION_ROLE_UPDATED,
            'Rol duzenlendi: ' . $this->record->name,
        );
    }
}
