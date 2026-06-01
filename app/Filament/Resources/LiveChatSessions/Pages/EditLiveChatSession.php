<?php

namespace App\Filament\Resources\LiveChatSessions\Pages;

use App\Filament\Resources\LiveChatSessions\LiveChatSessionResource;
use App\Models\LiveChatMessage;
use App\Models\LiveChatSession;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditLiveChatSession extends EditRecord
{
    protected static string $resource = LiveChatSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('reply')
                ->label('Cevap Yaz')
                ->visible(fn (): bool => auth()->user()?->hasPermission('live_chat_reply') ?? false)
                ->schema([
                    Textarea::make('message')
                        ->label('Mesaj')
                        ->required()
                        ->rows(5),
                ])
                ->action(function (array $data): void {
                    /** @var LiveChatSession $session */
                    $session = $this->record;

                    if ($session->status === LiveChatSession::STATUS_CLOSED) {
                        Notification::make()
                            ->title('Kapali sohbete cevap yazilamaz.')
                            ->warning()
                            ->send();

                        return;
                    }

                    $session->messages()->create([
                        'sender_type' => LiveChatMessage::SENDER_ADMIN,
                        'sender_id' => auth()->id(),
                        'message' => $data['message'],
                        'created_at' => now(),
                    ]);

                    $session->forceFill([
                        'status' => LiveChatSession::STATUS_ANSWERED,
                        'assigned_user_id' => $session->assigned_user_id ?: auth()->id(),
                    ])->save();

                    Notification::make()
                        ->title('Cevap eklendi.')
                        ->success()
                        ->send();
                }),
            Action::make('close')
                ->label('Sohbeti Kapat')
                ->color('danger')
                ->requiresConfirmation()
                ->visible(fn (): bool => (auth()->user()?->hasPermission('live_chat_close') ?? false) && $this->record->status !== LiveChatSession::STATUS_CLOSED)
                ->action(function (): void {
                    /** @var LiveChatSession $session */
                    $session = $this->record;

                    if ($session->status !== LiveChatSession::STATUS_CLOSED) {
                        $session->forceFill([
                            'status' => LiveChatSession::STATUS_CLOSED,
                            'closed_at' => now(),
                            'assigned_user_id' => $session->assigned_user_id ?: auth()->id(),
                        ])->save();

                        $session->messages()->create([
                            'sender_type' => LiveChatMessage::SENDER_SYSTEM,
                            'sender_id' => auth()->id(),
                            'message' => 'Yetkili görüşmeyi sonlandırdı.',
                            'created_at' => now(),
                        ]);
                    }
                }),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (($data['status'] ?? null) === LiveChatSession::STATUS_CLOSED && $this->record->closed_at === null) {
            $data['closed_at'] = now();
        }

        if (($data['status'] ?? null) !== LiveChatSession::STATUS_CLOSED) {
            $data['closed_at'] = null;
        }

        return $data;
    }
}
