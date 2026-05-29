<?php

namespace App\Filament\Resources\HeroButtons\Tables;

use App\Models\HeroButton;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class HeroButtonsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Buton Başlığı')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('url')
                    ->label('Link')
                    ->searchable(),
                TextColumn::make('style')
                    ->label('Stil')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => HeroButton::styleOptions()[$state] ?? $state)
                    ->color(fn (string $state): string => HeroButton::styleColors()[$state] ?? 'gray')
                    ->sortable(),
                TextColumn::make('target')
                    ->label('Hedef')
                    ->formatStateUsing(fn (string $state): string => HeroButton::targetOptions()[$state] ?? $state)
                    ->sortable(),
                TextColumn::make('sort_order')
                    ->label('Sıralama')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('Aktif mi')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Aktiflik Durumu')
                    ->trueLabel('Aktif Butonlar')
                    ->falseLabel('Pasif Butonlar')
                    ->placeholder('Tüm Butonlar'),
                SelectFilter::make('style')
                    ->label('Stil')
                    ->options(HeroButton::styleOptions()),
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
