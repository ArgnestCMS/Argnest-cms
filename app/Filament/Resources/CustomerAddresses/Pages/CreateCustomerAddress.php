<?php

namespace App\Filament\Resources\CustomerAddresses\Pages;

use App\Filament\Resources\CustomerAddresses\CustomerAddressResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomerAddress extends CreateRecord
{
    protected static string $resource = CustomerAddressResource::class;
}
