<?php

namespace App\Filament\Resources\AdminUsers\Pages;

use App\Filament\Resources\AdminUsers\AdminUserResource;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Hash;

class EditAdminUser extends EditRecord
{
    protected static string $resource = AdminUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->label('Sil')
                ->before(function (DeleteAction $action): void {
                    $message = AdminUserResource::getDeleteBlockMessage($this->record);

                    if ($message === null) {
                        return;
                    }

                    Notification::make()
                        ->title($message)
                        ->danger()
                        ->send();

                    $action->cancel();
                }),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['role'] = User::ROLE_ADMIN;

        if (array_key_exists('password', $data)) {
            $data['password'] = Hash::make($data['password']);
        }

        if ($this->record->id === auth()->id()) {
            $data['is_active'] = true;
        }

        if (($data['is_active'] ?? true) === false) {
            $message = AdminUserResource::getDeactivateBlockMessage($this->record);

            if ($message !== null) {
                Notification::make()
                    ->title($message)
                    ->danger()
                    ->send();

                $data['is_active'] = true;
            }
        }

        return $data;
    }
}
