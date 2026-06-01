<?php

namespace App\Filament\Resources\Permissions\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PermissionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Yetki Bilgileri')
                    ->schema([
                        TextInput::make('name')
                            ->label('Yetki Adi')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('key')
                            ->label('Key')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        TextInput::make('group')
                            ->label('Grup')
                            ->required()
                            ->maxLength(255),
                        Textarea::make('description')
                            ->label('Aciklama')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
