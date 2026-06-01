<?php

namespace App\Filament\Resources\SupportTickets\Pages;

use App\Filament\Resources\SupportTickets\SupportTicketResource;
use App\Models\CustomerNotification;
use App\Models\SupportMessage;
use App\Models\SupportTicket;
use App\Services\SupportTicketMailService;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;

class EditSupportTicket extends EditRecord
{
    protected static string $resource = SupportTicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('reply')
                ->label('Admin Cevabi Yaz')
                ->schema([
                    Textarea::make('message')
                        ->label('Mesaj')
                        ->required()
                        ->rows(6),
                    FileUpload::make('attachments')
                        ->label('Ek Dosyalar')
                        ->disk('local')
                        ->directory('support')
                        ->multiple()
                        ->preserveFilenames()
                        ->maxSize(20480)
                        ->rules(['mimes:pdf,doc,docx,xls,xlsx,txt,jpg,jpeg,png,webp,zip,rar']),
                ])
                ->action(function (array $data): void {
                    /** @var SupportTicket $ticket */
                    $ticket = $this->record;

                    $message = $ticket->messages()->create([
                        'user_id' => auth()->id(),
                        'is_admin' => true,
                        'message' => $data['message'],
                        'created_at' => now(),
                    ]);

                    $this->attachFiles($message, $data['attachments'] ?? []);

                    $ticket->forceFill(['status' => SupportTicket::STATUS_ANSWERED])->save();

                    app(SupportTicketMailService::class)->adminReplied($ticket, $message);

                    CustomerNotification::query()->create([
                        'user_id' => $ticket->user_id,
                        'title' => 'Destek talebinize cevap geldi',
                        'message' => $ticket->ticket_no . ' numarali destek talebinize admin cevabi eklendi.',
                        'type' => 'support',
                        'link' => route('frontend.customer.support.show', $ticket),
                    ]);
                }),
            DeleteAction::make()
                ->label('Sil'),
        ];
    }

    private function attachFiles(SupportMessage $message, array|string|null $paths): void
    {
        foreach ((array) $paths as $path) {
            if (blank($path)) {
                continue;
            }

            $disk = Storage::disk('local');

            $message->attachments()->create([
                'original_name' => basename($path),
                'file_path' => $path,
                'file_size' => $disk->exists($path) ? $disk->size($path) : 0,
                'mime_type' => $disk->exists($path) ? $disk->mimeType($path) : null,
                'created_at' => now(),
            ]);
        }
    }
}
