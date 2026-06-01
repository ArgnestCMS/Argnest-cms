<?php

namespace App\Filament\Resources\CustomerServices\Pages;

use App\Filament\Resources\CustomerServices\CustomerServiceResource;
use App\Models\CustomerNotification;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomerService extends CreateRecord
{
    protected static string $resource = CustomerServiceResource::class;

    protected function afterCreate(): void
    {
        CustomerNotification::query()->create([
            'user_id' => $this->record->user_id,
            'title' => 'Yeni hizmet kaydi olusturuldu',
            'message' => $this->record->service_name . ' hizmet kaydi musteri panelinize eklendi.',
            'type' => 'service',
            'link' => route('frontend.customer.services'),
        ]);
    }
}
