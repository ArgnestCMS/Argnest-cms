<?php

namespace App\Filament\Resources\SupportTickets\Pages;

use App\Filament\Resources\SupportTickets\SupportTicketResource;
use App\Models\SupportTicket;
use Filament\Resources\Pages\CreateRecord;

class CreateSupportTicket extends CreateRecord
{
    protected static string $resource = SupportTicketResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['ticket_no'] = SupportTicket::generateTicketNo();

        return $data;
    }

    protected function afterCreate(): void
    {
        $message = $this->data['admin_initial_message'] ?? null;

        if (blank($message)) {
            return;
        }

        $this->record->messages()->create([
            'user_id' => auth()->id(),
            'is_admin' => true,
            'message' => $message,
            'created_at' => now(),
        ]);
    }
}
