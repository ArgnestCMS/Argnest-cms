<?php

namespace App\Filament\Resources\Leads\Schemas;

use App\Models\Lead;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LeadForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Talep Bilgileri')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Ad Soyad')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('email')
                                    ->label('E-posta')
                                    ->email()
                                    ->maxLength(255),
                                TextInput::make('phone')
                                    ->label('Telefon')
                                    ->tel()
                                    ->maxLength(255),
                                TextInput::make('company')
                                    ->label('Firma')
                                    ->maxLength(255),
                                TextInput::make('service_type')
                                    ->label('Hizmet Türü')
                                    ->maxLength(255),
                                TextInput::make('budget_range')
                                    ->label('Bütçe Aralığı')
                                    ->maxLength(255),
                                Textarea::make('message')
                                    ->label('Mesaj')
                                    ->required()
                                    ->rows(5)
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->columnSpanFull(),
                Section::make('Yönetim Bilgileri')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('status')
                                    ->label('Durum')
                                    ->options(Lead::statusOptions())
                                    ->default(Lead::STATUS_NEW)
                                    ->required(),
                                TextInput::make('source')
                                    ->label('Kaynak')
                                    ->maxLength(255),
                                DateTimePicker::make('last_contacted_at')
                                    ->label('Son Görüşme Tarihi')
                                    ->seconds(false),
                                Textarea::make('admin_note')
                                    ->label('Yönetici Notu')
                                    ->rows(4)
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
