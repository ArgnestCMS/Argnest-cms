<?php

namespace App\Filament\Resources\CustomerTags\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CustomerTagForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Etiket Bilgileri')
                    ->schema([
                        TextInput::make('name')
                            ->label('Etiket Adi')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        ColorPicker::make('color')
                            ->label('Renk'),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
