<?php

namespace App\Filament\Resources\Portfolios\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PortfoliosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Proje Adı')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('client_name')
                    ->label('Müşteri')
                    ->searchable()
                    ->sortable(),
                IconColumn::make('is_featured')
                    ->label('Öne Çıkarıldı mı')
                    ->boolean()
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('Aktif mi')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('completion_date')
                    ->label('Tamamlanma Tarihi')
                    ->date('d.m.Y')
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Aktiflik Durumu')
                    ->trueLabel('Aktif Referanslar')
                    ->falseLabel('Pasif Referanslar')
                    ->placeholder('Tüm Referanslar'),
                Filter::make('featured')
                    ->label('Öne Çıkan Referanslar')
                    ->query(fn (Builder $query): Builder => $query->where('is_featured', true)),
            ])
            ->defaultSort('sort_order')
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
