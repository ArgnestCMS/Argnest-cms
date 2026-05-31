<?php

namespace App\Filament\Resources\Customers\Schemas;

use App\Models\User;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Müşteri Bilgileri')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Ad Soyad')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('company_name')
                                    ->label('Firma')
                                    ->maxLength(255),
                                TextInput::make('email')
                                    ->label('E-posta')
                                    ->email()
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255),
                                TextInput::make('phone')
                                    ->label('Telefon')
                                    ->tel()
                                    ->maxLength(255),
                                TextInput::make('password')
                                    ->label('Şifre')
                                    ->password()
                                    ->minLength(8)
                                    ->required(fn (string $operation): bool => $operation === 'create')
                                    ->dehydrated(fn (?string $state): bool => filled($state))
                                    ->maxLength(255),
                                Toggle::make('is_active')
                                    ->label('Aktif/Pasif')
                                    ->default(true)
                                    ->inline(false),
                                TextInput::make('role')
                                    ->default(User::ROLE_CUSTOMER)
                                    ->dehydrated()
                                    ->hidden(),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
