<?php

namespace App\Filament\Resources\CustomerFiles\Pages;

use App\Filament\Resources\CustomerFiles\CustomerFileResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;

class EditCustomerFile extends EditRecord
{
    protected static string $resource = CustomerFileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->label('Sil'),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $oldPath = $this->record->file_path;
        $path = $data['file_path'] ?? $oldPath;

        if ($path !== $oldPath && $oldPath && Storage::disk('local')->exists($oldPath)) {
            Storage::disk('local')->delete($oldPath);
        }

        if ($path !== $oldPath) {
            $disk = Storage::disk('local');

            $data['file_name'] = $path ? basename($path) : $this->record->file_name;
            $data['file_size'] = $path && $disk->exists($path) ? $disk->size($path) : 0;
            $data['mime_type'] = $path && $disk->exists($path) ? $disk->mimeType($path) : null;
        }

        return $data;
    }
}
