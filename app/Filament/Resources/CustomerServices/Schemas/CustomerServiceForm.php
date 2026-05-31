<?php

namespace App\Filament\Resources\CustomerServices\Schemas;

use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CustomerServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Müşteri ve Hizmet')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('user_id')
                                    ->label('Müşteri')
                                    ->relationship(
                                        'customer',
                                        'name',
                                        fn ($query) => $query
                                            ->where('role', User::ROLE_CUSTOMER)
                                            ->where('is_active', true)
                                            ->orderBy('name'),
                                    )
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                                TextInput::make('service_name')
                                    ->label('Hizmet adı')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('domain_name')
                                    ->label('Domain')
                                    ->maxLength(255),
                                TextInput::make('hosting_package')
                                    ->label('Hosting')
                                    ->maxLength(255),
                            ]),
                    ])
                    ->columnSpanFull(),
                Section::make('Teknik Bilgiler')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('server_ip')
                                    ->label('Sunucu IP')
                                    ->maxLength(255),
                                TextInput::make('server_panel')
                                    ->label('Sunucu Paneli')
                                    ->maxLength(255),
                                TextInput::make('username')
                                    ->label('Kullanıcı Adı')
                                    ->maxLength(255),
                                TextInput::make('password_hint')
                                    ->label('Şifre Notu')
                                    ->maxLength(255),
                                DatePicker::make('expiry_date')
                                    ->label('Son kullanım tarihi'),
                                Toggle::make('is_active')
                                    ->label('Aktif/Pasif')
                                    ->default(true)
                                    ->inline(false),
                                Textarea::make('notes')
                                    ->label('Notlar')
                                    ->rows(5)
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
