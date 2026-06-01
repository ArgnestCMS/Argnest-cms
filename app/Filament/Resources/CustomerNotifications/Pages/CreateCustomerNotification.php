<?php

namespace App\Filament\Resources\CustomerNotifications\Pages;

use App\Filament\Resources\CustomerNotifications\CustomerNotificationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomerNotification extends CreateRecord
{
    protected static string $resource = CustomerNotificationResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
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
