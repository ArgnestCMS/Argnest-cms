<?php

namespace App\Filament\Resources\CustomerTags\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CustomerTagsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Etiket')
                    ->searchable()
                    ->sortable(),
                ColorColumn::make('color')
                    ->label('Renk'),
                TextColumn::make('customers_count')
                    ->label('Musteri Sayisi')
                    ->counts('customers')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Olusturulma')
                    ->dateTime('d.m.Y H:i', timezone: 'Europe/Istanbul')
                    ->sortable(),
            ])
            ->defaultSort('name')
            ->recordActions([
                EditAction::make()
                    ->label('Duzenle'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Secilenleri Sil'),
                ]),
            ]);
    }
}
