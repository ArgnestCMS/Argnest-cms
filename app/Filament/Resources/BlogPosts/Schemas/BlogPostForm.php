<?php

namespace App\Filament\Resources\BlogPosts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class BlogPostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Genel Bilgiler')
                    ->schema([
                        TextInput::make('title')
                            ->label('Başlık')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state ?? ''))),
                        TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Select::make('blog_category_id')
                            ->label('Kategori')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Section::make('İçerik')
                    ->schema([
                        Textarea::make('excerpt')
                            ->label('Özet')
                            ->rows(3)
                            ->columnSpanFull(),
                        RichEditor::make('content')
                            ->label('İçerik')
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
                Section::make('Görseller')
                    ->schema([
                        FileUpload::make('featured_image')
                            ->label('Öne Çıkan Görsel')
                            ->disk('public')
                            ->directory('blog')
                            ->image()
                            ->imageEditor()
                            ->helperText('Öne çıkan görsel public diskinde blog klasörüne yüklenecek.'),
                        FileUpload::make('gallery')
                            ->label('Galeri')
                            ->disk('public')
                            ->directory('blog')
                            ->image()
                            ->multiple()
                            ->reorderable()
                            ->helperText('Galeri görselleri public diskinde blog klasörüne yüklenecek.'),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Section::make('Yayın')
                    ->schema([
                        TextInput::make('author')
                            ->label('Yazar')
                            ->maxLength(255),
                        DateTimePicker::make('published_at')
                            ->label('Yayın Tarihi')
                            ->seconds(false),
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
            ]);
    }
}
