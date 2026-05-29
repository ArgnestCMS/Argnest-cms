<?php

namespace App\Filament\Resources\HeroButtons\Pages;

use App\Filament\Resources\HeroButtons\HeroButtonResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListHeroButtons extends ListRecords
{
    protected static string $resource = HeroButtonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Yeni Hero Butonu'),
        ];
    }
}
