<?php

namespace App\Filament\Resources\CustomerFiles\Pages;

use App\Filament\Resources\CustomerFiles\CustomerFileResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCustomerFiles extends ListRecords
{
    protected static string $resource = CustomerFileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Yeni Dosya'),
        ];
    }
}
