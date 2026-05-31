<?php

namespace App\Filament\Resources\CustomerFiles\Schemas;

use App\Models\CustomerFile;
use App\Models\User;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CustomerFileForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Dosya Bilgileri')
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
                                    ->label('Baslik')
                                    ->required()
                                    ->maxLength(255),
                                Select::make('category')
                                    ->label('Kategori')
                                    ->options(CustomerFile::categoryOptions())
                                    ->default(CustomerFile::CATEGORY_OTHER)
                                    ->required(),
                                Toggle::make('is_visible')
                                    ->label('Musteriye gorunsun')
                                    ->default(true)
                                    ->inline(false),
                                Textarea::make('description')
                                    ->label('Aciklama')
                                    ->rows(4)
                                    ->columnSpanFull(),
                                FileUpload::make('file_path')
                                    ->label('Dosya')
                                    ->disk('local')
                                    ->directory('customer-files')
                                    ->preserveFilenames()
                                    ->required(fn (string $operation): bool => $operation === 'create')
                                    ->downloadable()
                                    ->openable(false)
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
