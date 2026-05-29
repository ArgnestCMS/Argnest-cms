<?php

namespace App\Filament\Resources\HeroButtons\Pages;

use App\Filament\Resources\HeroButtons\HeroButtonResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditHeroButton extends EditRecord
{
    protected static string $resource = HeroButtonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->label('Sil'),
        ];
    }
}
