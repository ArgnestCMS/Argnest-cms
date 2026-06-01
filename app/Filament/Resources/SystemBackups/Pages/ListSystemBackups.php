<?php

namespace App\Filament\Resources\SystemBackups\Pages;

use App\Filament\Resources\SystemBackups\SystemBackupResource;
use App\Models\SystemBackup;
use App\Services\SystemBackupService;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListSystemBackups extends ListRecords
{
    protected static string $resource = SystemBackupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('createFullBackup')
                ->label('Yeni Tam Yedek Olustur')
                ->icon('heroicon-o-archive-box-arrow-down')
                ->requiresConfirmation()
                ->modalHeading('Tam sistem yedegi olusturulsun mu?')
                ->modalDescription('Veritabani ve dosya arsivi senkron olarak hazirlanacak. Buyuk sistemlerde islem biraz surebilir.')
                ->visible(fn (): bool => SystemBackupResource::canCreate())
                ->action(function (): void {
                    $backup = app(SystemBackupService::class)->createFullBackup(auth()->user());
                    $notification = Notification::make()
                        ->title($backup->status === SystemBackup::STATUS_COMPLETED ? 'Yedek hazirlandi' : 'Yedek olusturulamadi')
                        ->body($backup->status === SystemBackup::STATUS_COMPLETED ? $backup->file_name : $backup->error_message);

                    if ($backup->status === SystemBackup::STATUS_COMPLETED) {
                        $notification->success();
                    } else {
                        $notification->danger();
                    }

                    $notification->send();
                }),
        ];
    }
}
