<?php

namespace App\Filament\Resources\SystemBackups\Tables;

use App\Filament\Resources\SystemBackups\SystemBackupResource;
use App\Models\AdminActivityLog;
use App\Models\SystemBackup;
use App\Services\AdminActivityLogger;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\File;

class SystemBackupsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('file_name')
                    ->label('Dosya')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->label('Tur')
                    ->badge()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Durum')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => SystemBackup::statusOptions()[$state] ?? $state)
                    ->color(fn (string $state): string => SystemBackup::statusColors()[$state] ?? 'gray')
                    ->sortable(),
                TextColumn::make('file_size')
                    ->label('Boyut')
                    ->getStateUsing(fn (SystemBackup $record): string => $record->formattedSize())
                    ->sortable(),
                TextColumn::make('creator.name')
                    ->label('Olusturan')
                    ->placeholder('-')
                    ->sortable(),
                TextColumn::make('completed_at')
                    ->label('Tamamlanma')
                    ->dateTime('d.m.Y H:i', timezone: 'Europe/Istanbul')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Olusturulma')
                    ->dateTime('d.m.Y H:i', timezone: 'Europe/Istanbul')
                    ->sortable(),
                TextColumn::make('error_message')
                    ->label('Hata')
                    ->limit(80)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Durum')
                    ->options(SystemBackup::statusOptions()),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                Action::make('download')
                    ->label('Indir')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->visible(fn (SystemBackup $record): bool => SystemBackupResource::canDownload()
                        && $record->status === SystemBackup::STATUS_COMPLETED
                        && File::exists($record->absolutePath()))
                    ->action(function (SystemBackup $record) {
                        app(AdminActivityLogger::class)->log(
                            AdminActivityLog::ACTION_BACKUP_DOWNLOADED,
                            'Tam sistem yedegi indirildi: ' . $record->file_name,
                        );

                        return response()->download($record->absolutePath(), $record->file_name);
                    }),
                DeleteAction::make()
                    ->label('Sil')
                    ->visible(fn (SystemBackup $record): bool => SystemBackupResource::canDelete($record))
                    ->before(function (SystemBackup $record): void {
                        if (File::exists($record->absolutePath())) {
                            File::delete($record->absolutePath());
                        }

                        app(AdminActivityLogger::class)->log(
                            AdminActivityLog::ACTION_BACKUP_DELETED,
                            'Tam sistem yedegi silindi: ' . $record->file_name,
                        );
                    }),
            ]);
    }
}
