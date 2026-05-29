<?php

namespace App\Filament\Resources\SiteSettings\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SiteSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Genel Bilgiler')
                    ->schema([
                        TextInput::make('site_name')
                            ->label('Site Adı')
                            ->required()
                            ->default('Argnest')
                            ->maxLength(255),
                        TextInput::make('site_slogan')
                            ->label('Slogan')
                            ->maxLength(255),
                        Textarea::make('site_description')
                            ->label('Site Açıklaması')
                            ->rows(4)
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Section::make('İletişim Bilgileri')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('phone')
                                    ->label('Telefon')
                                    ->tel()
                                    ->maxLength(255),
                                TextInput::make('email')
                                    ->label('E-posta')
                                    ->email()
                                    ->maxLength(255),
                                TextInput::make('whatsapp')
                                    ->label('WhatsApp')
                                    ->tel()
                                    ->maxLength(255),
                                Textarea::make('address')
                                    ->label('Adres')
                                    ->rows(3)
                                    ->columnSpanFull(),
                                TextInput::make('google_maps_url')
                                    ->label('Google Harita Linki')
                                    ->url()
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->columnSpanFull(),
                Section::make('Görsel Ayarlar')
                    ->schema([
                        FileUpload::make('logo')
                            ->label('Logo')
                            ->disk('public')
                            ->directory('site')
                            ->image()
                            ->imageEditor()
                            ->helperText('Logo dosyası public diskinde site klasörüne yüklenecek.'),
                        FileUpload::make('hero_banner')
                            ->label('Ana Sayfa Hero Banner')
                            ->disk('public')
                            ->directory('site/hero')
                            ->image()
                            ->helperText('Ana sayfada en üstte gösterilecek tek parça hero tanıtım görselidir. Görselin içinde başlık, logo, açıklama ve tanıtım öğeleri bulunabilir.'),
                        FileUpload::make('hero_background')
                            ->label('Hero Arka Plan Görseli')
                            ->disk('public')
                            ->directory('site/hero')
                            ->image()
                            ->helperText('Ana sayfa hero bölümünün arka planında kullanılacak koyu teknoloji temalı görseldir.'),
                        FileUpload::make('favicon')
                            ->label('Favicon')
                            ->disk('public')
                            ->directory('site')
                            ->image()
                            ->helperText('Favicon dosyası public diskinde site klasörüne yüklenecek.'),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Section::make('Sosyal Medya')
                    ->schema([
                        TextInput::make('facebook_url')
                            ->label('Facebook')
                            ->url()
                            ->maxLength(255),
                        TextInput::make('instagram_url')
                            ->label('Instagram')
                            ->url()
                            ->maxLength(255),
                        TextInput::make('linkedin_url')
                            ->label('LinkedIn')
                            ->url()
                            ->maxLength(255),
                        TextInput::make('youtube_url')
                            ->label('YouTube')
                            ->url()
                            ->maxLength(255),
                        TextInput::make('x_url')
                            ->label('X / Twitter')
                            ->url()
                            ->maxLength(255),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Section::make('SEO Ayarları')
                    ->schema([
                        TextInput::make('seo_title')
                            ->label('SEO Başlığı')
                            ->maxLength(255),
                        Textarea::make('seo_description')
                            ->label('SEO Açıklaması')
                            ->rows(3),
                        Textarea::make('seo_keywords')
                            ->label('SEO Anahtar Kelimeler')
                            ->rows(3)
                            ->helperText('Anahtar kelimeleri virgülle ayırabilirsiniz.'),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Section::make('Yasal Metinler')
                    ->schema([
                        Textarea::make('kvkk_text')
                            ->label('KVKK Aydınlatma Metni')
                            ->rows(6),
                        Textarea::make('privacy_policy')
                            ->label('Gizlilik Politikası')
                            ->rows(6),
                        Textarea::make('cookie_policy')
                            ->label('Çerez Politikası')
                            ->rows(6),
                        Textarea::make('information_security_policy')
                            ->label('Bilgi Güvenliği Politikası')
                            ->rows(6),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Section::make('Footer')
                    ->schema([
                        Textarea::make('footer_text')
                            ->label('Footer Yazısı')
                            ->rows(3),
                        TextInput::make('copyright_text')
                            ->label('Copyright Metni')
                            ->maxLength(255),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
