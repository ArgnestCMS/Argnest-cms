<?php

namespace App\Filament\Resources\CustomerActivityLogs\Pages;

use App\Filament\Resources\CustomerActivityLogs\CustomerActivityLogResource;
use Filament\Resources\Pages\ListRecords;

class ListCustomerActivityLogs extends ListRecords
{
    protected static string $resource = CustomerActivityLogResource::class;
}
