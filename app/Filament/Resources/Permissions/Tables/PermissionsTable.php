<?php

namespace App\Filament\Resources\Permissions\Tables;

use App\Models\AdminActivityLog;
use App\Models\Permission;
use App\Services\AdminActivityLogger;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PermissionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Yetki Adi')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('key')
                    ->label('Key')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('group')
                    ->label('Grup')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Olusturulma Tarihi')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('group')
            ->recordActions([
                EditAction::make()
                    ->label('Duzenle'),
                DeleteAction::make()
                    ->after(function (Permission $record): void {
                        app(AdminActivityLogger::class)->log(
                            AdminActivityLog::ACTION_PERMISSION_DELETED,
                            'Yetki silindi: ' . $record->name . ' (' . $record->key . ')',
                        );
                    })
                    ->label('Sil'),
            ]);
    }
}
