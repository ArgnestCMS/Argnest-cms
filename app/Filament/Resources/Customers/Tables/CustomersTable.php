<?php

namespace App\Filament\Resources\Customers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
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
