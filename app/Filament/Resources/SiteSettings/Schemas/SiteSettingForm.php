<?php

namespace App\Filament\Resources\SiteSettings\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
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
                Section::make('Mail Ayarları')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('smtp_host')
                                    ->label('SMTP Host')
                                    ->maxLength(255),
                                TextInput::make('smtp_port')
                                    ->label('SMTP Port')
                                    ->numeric()
                                    ->minValue(1)
                                    ->maxValue(65535),
                                TextInput::make('smtp_username')
                                    ->label('SMTP Kullanici Adi')
                                    ->maxLength(255),
                                TextInput::make('smtp_password')
                                    ->label('SMTP Sifresi')
                                    ->password()
                                    ->revealable()
                                    ->afterStateHydrated(fn ($component) => $component->state(null))
                                    ->dehydrated(fn (?string $state): bool => filled($state))
                                    ->helperText('Bos birakilirsa mevcut sifre korunur.'),
                                Select::make('smtp_encryption')
                                    ->label('SMTP Sifreleme')
                                    ->options([
                                        'tls' => 'TLS',
                                        'ssl' => 'SSL',
                                        'none' => 'Yok',
                                    ])
                                    ->native(false),
                                TextInput::make('mail_from_address')
                                    ->label('Gonderen E-posta')
                                    ->email()
                                    ->maxLength(255),
                                TextInput::make('mail_from_name')
                                    ->label('Gonderen Adi')
                                    ->maxLength(255),
                                TextInput::make('admin_notification_email')
                                    ->label('Admin Bildirim E-postasi')
                                    ->helperText('Birden fazla alici icin virgul veya noktalı virgul kullanabilirsiniz.')
                                    ->maxLength(255),
                                TextInput::make('support_notification_email')
                                    ->label('Destek Bildirim E-postasi')
                                    ->helperText('Destek bildirimlerinde ilk oncelikli alici.')
                                    ->maxLength(255),
                                TextInput::make('sales_notification_email')
                                    ->label('Satis Bildirim E-postasi')
                                    ->maxLength(255),
                            ]),
                    ])
                    ->columnSpanFull(),
                Section::make('Musteri Ayarlari')
                    ->schema([
                        Toggle::make('customer_email_verification_enabled')
                            ->label('Musteri E-posta Dogrulama Aktif')
                            ->default(false)
                            ->inline(false),
                    ])
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
