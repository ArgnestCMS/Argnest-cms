<?php

namespace App\Filament\Resources\BlogCategories\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class BlogCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Genel Bilgiler')
                    ->schema([
                        TextInput::make('name')
                            ->label('Kategori Adı')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state ?? ''))),
                        TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Textarea::make('description')
                            ->label('Açıklama')
                            ->rows(4)
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Section::make('Ayarlar')
                    ->schema([
                        Toggle::make('is_active')
                            ->label('Aktif/Pasif')
                            ->default(true)
                            ->inline(false),
                        TextInput::make('sort_order')
                            ->label('Sıralama')
                            ->numeric()
                            ->default(0)
                            ->required(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Section::make('SEO')
                    ->schema([
                        TextInput::make('seo_title')
                            ->label('SEO Başlığı')
                            ->maxLength(255),
                        Textarea::make('seo_description')
                            ->label('SEO Açıklaması')
                            ->rows(3),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
