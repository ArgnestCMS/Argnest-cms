<?php

namespace App\Filament\Resources\Roles\Tables;

use App\Models\Role;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RolesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Rol Adi')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('permissions_count')
                    ->label('Yetki Sayisi')
                    ->counts('permissions')
                    ->sortable(),
                IconColumn::make('is_system')
                    ->label('Sistem')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Olusturulma Tarihi')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                EditAction::make()
                    ->label('Duzenle'),
                DeleteAction::make()
                    ->label('Sil')
                    ->before(function (DeleteAction $action, Role $record): void {
                        if (! $record->is_system) {
                            return;
                        }

                        Notification::make()
                            ->title('Sistem rolleri silinemez.')
                            ->danger()
                            ->send();

                        $action->cancel();
                    }),
            ]);
    }
}
