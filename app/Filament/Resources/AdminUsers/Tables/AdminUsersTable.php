<?php

namespace App\Filament\Resources\AdminUsers\Tables;

use App\Filament\Resources\AdminUsers\AdminUserResource;
use App\Models\AdminActivityLog;
use App\Models\User;
use App\Services\AdminActivityLogger;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AdminUsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Ad Soyad')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('E-posta')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('phone')
                    ->label('Telefon')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('roles.name')
                    ->label('Roller')
                    ->badge()
                    ->separator(', ')
                    ->toggleable(),
                IconColumn::make('is_active')
                    ->label('Aktif/Pasif')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Oluşturulma Tarihi')
                    ->dateTime('d.m.Y H:i', timezone: 'Europe/Istanbul')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('Güncellenme Tarihi')
                    ->dateTime('d.m.Y H:i', timezone: 'Europe/Istanbul')
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                EditAction::make()
                    ->label('Düzenle'),
                DeleteAction::make()
                    ->label('Sil')
                    ->after(function (User $record): void {
                        app(AdminActivityLogger::class)->log(
                            AdminActivityLog::ACTION_ADMIN_USER_DELETED,
                            'Admin kullanici silindi: ' . $record->name . ' (' . $record->email . ')',
                        );
                    })
                    ->before(function (DeleteAction $action, User $record): void {
                        $message = AdminUserResource::getDeleteBlockMessage($record);

                        if ($message === null) {
                            return;
                        }

                        Notification::make()
                            ->title($message)
                            ->danger()
                            ->send();

                        $action->cancel();
                    }),
            ]);
    }
}
