<?php

namespace App\Filament\Resources\CustomerServices\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CustomerServicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('customer.name')
                    ->label('Müşteri')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('service_name')
                    ->label('Hizmet adı')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('domain_name')
                    ->label('Domain')
                    ->searchable(),
                TextColumn::make('hosting_package')
                    ->label('Hosting')
                    ->searchable(),
                TextColumn::make('server_ip')
                    ->label('Sunucu')
                    ->searchable(),
                TextColumn::make('expiry_date')
                    ->label('Son kullanım tarihi')
                    ->date('d.m.Y')
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
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
