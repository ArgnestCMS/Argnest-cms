<?php

namespace App\Filament\Resources\Customers\Schemas;

use App\Models\User;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Müşteri Bilgileri')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Ad Soyad')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('company_name')
                                    ->label('Firma')
                                    ->maxLength(255),
                                TextInput::make('identity_number')
                                    ->label('TC Kimlik No')
                                    ->numeric()
                                    ->length(11)
                                    ->required()
                                    ->maxLength(11),
                                TextInput::make('email')
                                    ->label('E-posta')
                                    ->email()
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255),
                                TextInput::make('phone')
                                    ->label('Telefon')
                                    ->tel()
                                    ->maxLength(255),
                                TextInput::make('password')
                                    ->label('Şifre')
                                    ->password()
                                    ->minLength(8)
                                    ->required(fn (string $operation): bool => $operation === 'create')
                                    ->dehydrated(fn (?string $state): bool => filled($state))
                                    ->maxLength(255),
                                Toggle::make('is_active')
                                    ->label('Aktif/Pasif')
                                    ->default(true)
                                    ->inline(false),
                                TextInput::make('role')
                                    ->default(User::ROLE_CUSTOMER)
                                    ->dehydrated()
                                    ->hidden(),
                            ]),
                    ])
                    ->columnSpanFull(),
                Section::make('Güvenlik ve İzleme Bilgileri')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('registration_ip')
                                    ->label('Kayıt IP')
                                    ->disabled()
                                    ->dehydrated(false),
                                TextInput::make('last_login_at')
                                    ->label('Son Giriş Tarihi')
                                    ->formatStateUsing(fn (?User $record): string => $record?->last_login_at?->format('d.m.Y H:i') ?: 'Henüz yok')
                                    ->disabled()
                                    ->dehydrated(false),
                                TextInput::make('last_login_ip')
                                    ->label('Son Giriş IP')
                                    ->disabled()
                                    ->dehydrated(false),
                                TextInput::make('created_at')
                                    ->label('Kayıt Tarihi')
                                    ->formatStateUsing(fn (?User $record): string => $record?->created_at?->format('d.m.Y H:i') ?: 'Henüz yok')
                                    ->disabled()
                                    ->dehydrated(false),
                            ]),
                    ])
                    ->visible(fn (string $operation): bool => $operation === 'edit')
                    ->columnSpanFull(),
                Section::make('Müşteri Özeti / Son İşlemler')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Placeholder::make('services_count')
                                    ->label('Hizmet Sayısı')
                                    ->content(fn (?User $record): string => (string) ($record?->customerServices()->count() ?? 0)),
                                Placeholder::make('last_login_summary')
                                    ->label('Son Giriş Bilgisi')
                                    ->content(fn (?User $record): string => $record?->last_login_at
                                        ? $record->last_login_at->format('d.m.Y H:i') . ' / ' . ($record->last_login_ip ?: 'IP yok')
                                        : 'Henüz giriş kaydı yok.'),
                                Placeholder::make('registration_ip_summary')
                                    ->label('Kayıt IP Bilgisi')
                                    ->content(fn (?User $record): string => $record?->registration_ip ?: 'Kayıt IP bilgisi yok.'),
                                Placeholder::make('latest_services')
                                    ->label('Son Eklenen Hizmetler')
                                    ->content(function (?User $record): HtmlString {
                                        $services = $record?->customerServices()
                                            ->latest()
                                            ->take(3)
                                            ->get() ?? collect();

                                        if ($services->isEmpty()) {
                                            return new HtmlString('Henüz hizmet kaydı eklenmedi.');
                                        }

                                        return new HtmlString($services
                                            ->map(fn ($service): string => e($service->service_name) . ' - ' . e($service->created_at?->format('d.m.Y') ?: 'Tarih yok'))
                                            ->implode('<br>'));
                                    }),
                                Placeholder::make('upcoming_expiries')
                                    ->label('Yaklaşan Bitiş Tarihleri')
                                    ->content(function (?User $record): HtmlString {
                                        $services = $record?->customerServices()
                                            ->whereNotNull('expiry_date')
                                            ->whereDate('expiry_date', '>=', now())
                                            ->orderBy('expiry_date')
                                            ->take(3)
                                            ->get() ?? collect();

                                        if ($services->isEmpty()) {
                                            return new HtmlString('Yaklaşan bitiş tarihi olan hizmet yok.');
                                        }

                                        return new HtmlString($services
                                            ->map(fn ($service): string => e($service->service_name) . ' - ' . e($service->expiry_date?->format('d.m.Y') ?: 'Tarih yok'))
                                            ->implode('<br>'));
                                    }),
                                Placeholder::make('future_activity_placeholder')
                                    ->label('Teknik Destek / Yorumlar')
                                    ->content('Teknik destek ve yorum sistemi eklendiğinde son işlemler burada listelenecek.'),
                            ]),
                    ])
                    ->visible(fn (string $operation): bool => $operation === 'edit')
                    ->columnSpanFull(),
            ]);
    }
}
