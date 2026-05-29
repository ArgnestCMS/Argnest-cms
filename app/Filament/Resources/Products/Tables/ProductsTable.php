<?php

namespace App\Filament\Resources\Products\Tables;

use App\Models\Product;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Ürün Adı')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('product_status')
                    ->label('Durum')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => Product::statusOptions()[$state] ?? $state)
                    ->color(fn (string $state): string => Product::statusColors()[$state] ?? 'gray')
                    ->sortable(),
                IconColumn::make('is_featured')
                    ->label('Öne Çıkarıldı mı')
                    ->boolean()
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('Aktif mi')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('sort_order')
                    ->label('Sıralama')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Oluşturulma Tarihi')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Aktiflik Durumu')
                    ->trueLabel('Aktif Ürünler')
                    ->falseLabel('Pasif Ürünler')
                    ->placeholder('Tüm Ürünler'),
                Filter::make('featured')
                    ->label('Öne Çıkan Ürünler')
                    ->query(fn (Builder $query): Builder => $query->where('is_featured', true)),
                Filter::make('coming_soon')
                    ->label('Yakında Gelecek Ürünler')
                    ->query(fn (Builder $query): Builder => $query->where('product_status', Product::STATUS_COMING_SOON)),
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
