<?php

namespace App\Filament\Resources\CustomerNotifications\Schemas;

use App\Models\User;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CustomerNotificationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Bildirim Bilgileri')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('user_id')
                                    ->label('Müşteri')
                                    ->relationship('customer', 'name', fn ($query) => $query
                                        ->where('role', User::ROLE_CUSTOMER)
                                        ->orderBy('name'))
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                                TextInput::make('title')
                                    ->label('Başlık')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('type')
                                    ->label('Tür')
                                    ->maxLength(255),
                                TextInput::make('link')
                                    ->label('Link')
                                    ->maxLength(255),
                                Textarea::make('message')
                                    ->label('Mesaj')
                                    ->required()
                                    ->rows(5)
                                    ->columnSpanFull(),
                                Toggle::make('is_read')
                                    ->label('Okundu')
                                    ->inline(false),
                                DateTimePicker::make('read_at')
                                    ->label('Okunma Tarihi'),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
