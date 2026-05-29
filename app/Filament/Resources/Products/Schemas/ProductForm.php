<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Models\Product;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Genel Bilgiler')
                    ->schema([
                        TextInput::make('title')
                            ->label('Ürün Adı')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state ?? ''))),
                        TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Textarea::make('short_description')
                            ->label('Kısa Açıklama')
                            ->rows(3)
                            ->columnSpanFull(),
                        Textarea::make('description')
                            ->label('Detay Açıklama')
                            ->rows(8)
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Section::make('Görseller')
                    ->schema([
                        FileUpload::make('cover_image')
                            ->label('Kapak Görseli')
                            ->disk('public')
                            ->directory('products')
                            ->image()
                            ->imageEditor()
                            ->helperText('Kapak görseli public diskinde products klasörüne yüklenecek.'),
                        FileUpload::make('gallery')
                            ->label('Galeri Görselleri')
                            ->disk('public')
                            ->directory('products')
                            ->image()
                            ->multiple()
                            ->reorderable()
                            ->helperText('Galeri görselleri public diskinde products klasörüne yüklenecek.'),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Section::make('Durum')
                    ->schema([
                        Select::make('product_status')
                            ->label('Ürün Durumu')
                            ->options(Product::statusOptions())
                            ->default(Product::STATUS_ACTIVE)
                            ->required(),
                    ])
                    ->columnSpanFull(),
                Section::make('Öne Çıkarma')
                    ->schema([
                        Toggle::make('is_featured')
                            ->label('Öne Çıkar')
                            ->default(false)
                            ->inline(false),
                        Toggle::make('is_active')
                            ->label('Aktif/Pasif')
                            ->default(true)
                            ->inline(false),
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
                Section::make('Sıralama')
                    ->schema([
                        TextInput::make('sort_order')
                            ->label('Sıralama')
                            ->numeric()
                            ->default(0)
                            ->required(),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
