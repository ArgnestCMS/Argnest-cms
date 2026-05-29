<?php

namespace App\Filament\Resources\Portfolios\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PortfolioForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Genel Bilgiler')
                    ->schema([
                        TextInput::make('title')
                            ->label('Proje Adı')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state ?? ''))),
                        TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        TextInput::make('client_name')
                            ->label('Müşteri Adı')
                            ->maxLength(255),
                        TextInput::make('project_url')
                            ->label('Proje Adresi')
                            ->url()
                            ->maxLength(255),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Section::make('İçerik')
                    ->schema([
                        Textarea::make('short_description')
                            ->label('Kısa Açıklama')
                            ->rows(3)
                            ->columnSpanFull(),
                        Textarea::make('description')
                            ->label('Detay Açıklama')
                            ->rows(8)
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
                Section::make('Görseller')
                    ->schema([
                        FileUpload::make('cover_image')
                            ->label('Kapak Görseli')
                            ->disk('public')
                            ->directory('portfolio')
                            ->image()
                            ->imageEditor()
                            ->helperText('Kapak görseli public diskinde portfolio klasörüne yüklenecek.'),
                        FileUpload::make('gallery')
                            ->label('Galeri Görselleri')
                            ->disk('public')
                            ->directory('portfolio')
                            ->image()
                            ->multiple()
                            ->reorderable()
                            ->helperText('Galeri görselleri public diskinde portfolio klasörüne yüklenecek.'),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Section::make('Proje Bilgileri')
                    ->schema([
                        DatePicker::make('completion_date')
                            ->label('Tamamlanma Tarihi'),
                    ])
                    ->columnSpanFull(),
                Section::make('Durum')
                    ->schema([
                        Toggle::make('is_featured')
                            ->label('Öne Çıkar')
                            ->default(false)
                            ->inline(false),
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
                    ->columns(3)
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
