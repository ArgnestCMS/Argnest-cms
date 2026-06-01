<?php

namespace App\Filament\Resources\CustomerAddresses\Pages;

use App\Filament\Resources\CustomerAddresses\CustomerAddressResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCustomerAddress extends EditRecord
{
    protected static string $resource = CustomerAddressResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->label('Sil'),
        ];
    }
}
