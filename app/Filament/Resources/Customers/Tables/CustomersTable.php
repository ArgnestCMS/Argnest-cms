<?php

namespace App\Filament\Resources\Customers\Tables;

use App\Models\User;
use App\Services\CustomerEmailVerificationService;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CustomersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Ad Soyad')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('company_name')
                    ->label('Firma')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('phone')
                    ->label('Telefon')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Mail')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email_verified_at')
                    ->label('E-posta Dogrulama')
                    ->dateTime('d.m.Y H:i')
                    ->placeholder('Dogrulanmadi')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('identity_number')
                    ->label('TC Kimlik No')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('registration_ip')
                    ->label('Kayıt IP')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('last_login_at')
                    ->label('Son Giriş')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('last_login_ip')
                    ->label('Son Giriş IP')
                    ->searchable()
                    ->toggleable(),
                IconColumn::make('is_active')
                    ->label('Aktif/Pasif')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('customerServices_count')
                    ->label('Hizmet Sayısı')
                    ->counts('customerServices')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Kayıt Tarihi')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                Action::make('mark_email_verified')
                    ->label('E-postayi dogrulandi isaretle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (User $record): bool => $record->email_verified_at === null)
                    ->action(function (User $record): void {
                        app(CustomerEmailVerificationService::class)->markVerified($record);

                        Notification::make()
                            ->title('E-posta dogrulandi olarak isaretlendi.')
                            ->success()
                            ->send();
                    }),
                Action::make('resend_email_verification')
                    ->label('Dogrulama mailini tekrar gonder')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->visible(fn (User $record): bool => $record->email_verified_at === null)
                    ->action(function (User $record): void {
                        $sent = app(CustomerEmailVerificationService::class)->send($record);
                        $notification = Notification::make()
                            ->title($sent ? 'Dogrulama maili gonderildi.' : 'Dogrulama maili gonderilemedi, log kaydi olusturuldu.');

                        $sent ? $notification->success() : $notification->warning();
                        $notification->send();
                    }),
                EditAction::make()
                    ->label('Düzenle'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Seçilenleri Sil'),
                ]),
            ]);
    }
}
