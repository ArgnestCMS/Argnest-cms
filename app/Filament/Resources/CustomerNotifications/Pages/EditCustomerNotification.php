<?php

namespace App\Filament\Resources\CustomerNotifications\Pages;

use App\Filament\Resources\CustomerNotifications\CustomerNotificationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCustomerNotification extends EditRecord
{
    protected static string $resource = CustomerNotificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->label('Sil'),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (($data['is_read'] ?? false) && blank($data['read_at'] ?? null)) {
            $data['read_at'] = now();
        }

        if (! ($data['is_read'] ?? false)) {
            $data['read_at'] = null;
        }

        return $data;
    }
}
