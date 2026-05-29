<?php

namespace App\Filament\Resources\Leads\Tables;

use App\Models\Lead;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class LeadsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Ad Soyad')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('phone')
                    ->label('Telefon')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('E-posta')
                    ->searchable(),
                TextColumn::make('service_type')
                    ->label('Hizmet Türü')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Durum')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => Lead::statusOptions()[$state] ?? $state)
                    ->color(fn (string $state): string => Lead::statusColors()[$state] ?? 'gray')
                    ->sortable(),
                TextColumn::make('source')
                    ->label('Kaynak')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Oluşturulma Tarihi')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Durum')
                    ->options(Lead::statusOptions()),
                SelectFilter::make('service_type')
                    ->label('Hizmet Türü')
                    ->options(fn (): array => Lead::query()
                        ->whereNotNull('service_type')
                        ->where('service_type', '!=', '')
                        ->distinct()
                        ->orderBy('service_type')
                        ->pluck('service_type', 'service_type')
                        ->all()),
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
