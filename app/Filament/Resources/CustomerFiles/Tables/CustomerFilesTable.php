<?php

namespace App\Filament\Resources\CustomerFiles\Tables;

use App\Models\CustomerFile;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class CustomerFilesTable
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
                    ->label('Baslik')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => CustomerFile::categoryOptions()[$state] ?? $state)
                    ->sortable(),
                TextColumn::make('file_name')
                    ->label('Dosya Adi')
                    ->searchable()
                    ->limit(35),
                TextColumn::make('file_size')
                    ->label('Boyut')
                    ->getStateUsing(fn (CustomerFile $record): string => $record->formattedSize())
                    ->sortable(),
                IconColumn::make('is_visible')
                    ->label('Gorunur')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Yuklenme Tarihi')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->label('Kategori')
                    ->options(CustomerFile::categoryOptions()),
                TernaryFilter::make('is_visible')
                    ->label('Gorunurluk')
                    ->trueLabel('Gorunur')
                    ->falseLabel('Gizli')
                    ->placeholder('Tum Dosyalar'),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                Action::make('download')
                    ->label('Indir')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(fn (CustomerFile $record) => Storage::disk('local')->download($record->file_path, $record->file_name))
                    ->visible(fn (CustomerFile $record): bool => Storage::disk('local')->exists($record->file_path)),
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
