<?php

namespace App\Filament\Resources\Customers\Pages;

use App\Filament\Resources\Customers\CustomerResource;
use App\Models\User;
use Filament\Resources\Pages\EditRecord;

class EditCustomer extends EditRecord
{
    protected static string $resource = CustomerResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['role'] = User::ROLE_CUSTOMER;

        return $data;
    }

    protected function afterSave(): void
    {
        if ($this->record->wasChanged('admin_notes')) {
            $this->record->forceFill([
                'last_contact_at' => now(),
            ])->saveQuietly();
        }
    }
}
