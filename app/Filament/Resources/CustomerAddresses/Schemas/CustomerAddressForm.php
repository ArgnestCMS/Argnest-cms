<?php

namespace App\Filament\Resources\CustomerAddresses\Schemas;

use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CustomerAddressForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Adres Bilgileri')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('user_id')
                                    ->label('Musteri')
                                    ->relationship('customer', 'name', fn ($query) => $query
                                        ->where('role', User::ROLE_CUSTOMER)
                                        ->orderBy('name'))
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                                TextInput::make('title')
                                    ->label('Adres Basligi')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('country')
                                    ->label('Ulke')
                                    ->default('Turkiye')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('city')
                                    ->label('Il')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('district')
                                    ->label('Ilce')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('neighborhood')
                                    ->label('Mahalle')
                                    ->maxLength(255),
                                TextInput::make('street')
                                    ->label('Sokak/Cadde')
                                    ->maxLength(255),
                                TextInput::make('building_no')
                                    ->label('Bina No')
                                    ->maxLength(255),
                                TextInput::make('apartment_no')
                                    ->label('Daire No')
                                    ->maxLength(255),
                                TextInput::make('postal_code')
                                    ->label('Posta Kodu')
                                    ->maxLength(255),
                                Toggle::make('is_default')
                                    ->label('Varsayilan Adres')
                                    ->inline(false),
                                Textarea::make('address')
                                    ->label('Acik Adres')
                                    ->required()
                                    ->rows(5)
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
