<?php

namespace App\Filament\Resources\CustomerReviews\Schemas;

use App\Models\CustomerReview;
use App\Models\User;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CustomerReviewForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Yorum Bilgileri')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('user_id')
                                    ->label('Musteri')
                                    ->relationship('customer', 'name', fn ($query) => $query->where('role', User::ROLE_CUSTOMER))
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                                Select::make('rating')
                                    ->label('Puan')
                                    ->options([
                                        1 => '1 / 5',
                                        2 => '2 / 5',
                                        3 => '3 / 5',
                                        4 => '4 / 5',
                                        5 => '5 / 5',
                                    ]),
                                TextInput::make('title')
                                    ->label('Baslik')
                                    ->maxLength(255),
                                Select::make('status')
                                    ->label('Durum')
                                    ->options(CustomerReview::statusOptions())
                                    ->default(CustomerReview::STATUS_PENDING)
                                    ->required(),
                                Textarea::make('comment')
                                    ->label('Yorum')
                                    ->required()
                                    ->rows(6)
                                    ->columnSpanFull(),
                                Toggle::make('hide_name')
                                    ->label('Isim gizli')
                                    ->default(false),
                                Toggle::make('hide_contact')
                                    ->label('Iletisim gizli')
                                    ->default(true),
                            ]),
                    ])
                    ->columnSpanFull(),
                Section::make('Yonetim Bilgileri')
                    ->schema([
                        DateTimePicker::make('approved_at')
                            ->label('Onay Tarihi')
                            ->seconds(false),
                        Textarea::make('admin_note')
                            ->label('Admin Notu')
                            ->rows(4)
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
