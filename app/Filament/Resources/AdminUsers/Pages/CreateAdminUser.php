<?php

namespace App\Filament\Resources\AdminUsers\Pages;

use App\Filament\Resources\AdminUsers\AdminUserResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends CreateRecord
{
    protected static string $resource = AdminUserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['role'] = User::ROLE_ADMIN;
        $data['is_active'] = true;
        $data['password'] = Hash::make($data['password']);

        return $data;
    }
}
