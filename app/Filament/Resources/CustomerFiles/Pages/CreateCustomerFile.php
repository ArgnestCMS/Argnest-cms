<?php

namespace App\Filament\Resources\CustomerFiles\Pages;

use App\Filament\Resources\CustomerFiles\CustomerFileResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Storage;

class CreateCustomerFile extends CreateRecord
{
    protected static string $resource = CustomerFileResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $this->fillFileMetadata($data);
    }

    private function fillFileMetadata(array $data): array
    {
        $path = $data['file_path'] ?? null;
        $disk = Storage::disk('local');

        $data['file_name'] = $path ? basename($path) : ($data['file_name'] ?? '');
        $data['file_size'] = $path && $disk->exists($path) ? $disk->size($path) : 0;
        $data['mime_type'] = $path && $disk->exists($path) ? $disk->mimeType($path) : null;
        $data['uploaded_by'] = auth()->id();

        return $data;
    }
}
