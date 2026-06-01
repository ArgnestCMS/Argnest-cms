<?php

namespace App\Filament\Resources\CustomerAddresses\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class CustomerAddressesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('customer.name')
                    ->label('Musteri')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('title')
                    ->label('Adres Basligi')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('city')
                    ->label('Il')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('district')
                    ->label('Ilce')
                    ->searchable()
                    ->sortable(),
                IconColumn::make('is_default')
                    ->label('Varsayilan mi')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Olusturulma Tarihi')
                    ->dateTime('d.m.Y H:i', timezone: 'Europe/Istanbul')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                TernaryFilter::make('is_default')
                    ->label('Varsayilan Durumu'),
            ])
            ->defaultSort('created_at', 'desc')
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
