<?php

namespace App\Filament\Resources\HeroButtons\Schemas;

use App\Models\HeroButton;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class HeroButtonForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Buton Bilgileri')
                    ->schema([
                        TextInput::make('title')
                            ->label('Buton Başlığı')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('url')
                            ->label('Link')
                            ->required()
                            ->maxLength(255),
                        Select::make('style')
                            ->label('Stil')
                            ->options(HeroButton::styleOptions())
                            ->default(HeroButton::STYLE_PRIMARY)
                            ->required(),
                        Select::make('target')
                            ->label('Hedef')
                            ->options(HeroButton::targetOptions())
                            ->default(HeroButton::TARGET_SELF)
                            ->required(),
                        TextInput::make('icon')
                            ->label('İkon')
                            ->maxLength(255),
                        TextInput::make('sort_order')
                            ->label('Sıralama')
                            ->numeric()
                            ->default(0)
                            ->required(),
                        Toggle::make('is_active')
                            ->label('Aktif/Pasif')
                            ->default(true)
                            ->inline(false),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
