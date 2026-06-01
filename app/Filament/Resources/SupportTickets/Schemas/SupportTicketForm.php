<?php

namespace App\Filament\Resources\SupportTickets\Schemas;

use App\Models\SupportTicket;
use App\Models\User;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class SupportTicketForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Bilet Bilgileri')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('user_id')
                                    ->label('Musteri')
                                    ->relationship(
                                        'customer',
                                        'name',
                                        fn ($query) => $query
                                            ->where('role', User::ROLE_CUSTOMER)
                                            ->orderBy('name'),
                                    )
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                                TextInput::make('ticket_no')
                                    ->label('Ticket No')
                                    ->disabled()
                                    ->dehydrated(false),
                                TextInput::make('subject')
                                    ->label('Konu')
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpanFull(),
                                TextInput::make('category')
                                    ->label('Kategori')
                                    ->maxLength(255),
                                Select::make('priority')
                                    ->label('Oncelik')
                                    ->options(SupportTicket::priorityOptions())
                                    ->default(SupportTicket::PRIORITY_NORMAL)
                                    ->required(),
                                Select::make('status')
                                    ->label('Durum')
                                    ->options(SupportTicket::statusOptions())
                                    ->default(SupportTicket::STATUS_OPEN)
                                    ->required(),
                            ]),
                    ])
                    ->disabled(fn (): bool => ! (auth()->user()?->hasPermission('support_reply') ?? false))
                    ->columnSpanFull(),
                Section::make('Mesaj Gecmisi')
                    ->schema([
                        Placeholder::make('messages')
                            ->label('')
                            ->content(function (?SupportTicket $record): HtmlString {
                                $messages = $record?->messages()
                                    ->with(['user', 'attachments'])
                                    ->oldest('created_at')
                                    ->get() ?? collect();

                                if ($messages->isEmpty()) {
                                    return new HtmlString('Bu bilet icin henuz mesaj yok.');
                                }

                                return new HtmlString($messages
                                    ->map(function ($message): string {
                                        $sender = $message->is_admin ? 'Admin' : e($message->user?->name ?: 'Musteri');
                                        $date = e($message->created_at?->format('d.m.Y H:i') ?: '');
                                        $body = nl2br(e($message->message));
                                        $attachments = $message->attachments->isEmpty()
                                            ? ''
                                            : '<div style="margin-top:8px;font-size:12px;color:#475569;">Ekler: ' . e($message->attachments->pluck('original_name')->implode(', ')) . '</div>';

                                        return '<div style="border:1px solid #e2e8f0;border-radius:14px;padding:14px;margin-bottom:12px;background:#f8fafc;">'
                                            . '<strong>' . $sender . '</strong> <span style="color:#64748b;">' . $date . '</span>'
                                            . '<div style="margin-top:8px;">' . $body . '</div>'
                                            . $attachments
                                            . '</div>';
                                    })
                                    ->implode(''));
                            }),
                    ])
                    ->visible(fn (string $operation): bool => $operation === 'edit')
                    ->columnSpanFull(),
                Section::make('Ilk Admin Notu')
                    ->schema([
                        Textarea::make('admin_initial_message')
                            ->label('Mesaj')
                            ->rows(5)
                            ->dehydrated(false)
                            ->helperText('Opsiyonel. Bilet admin tarafindan olusturuluyorsa ilk mesaj olarak kaydedilir.'),
                    ])
                    ->visible(fn (string $operation): bool => $operation === 'create')
                    ->columnSpanFull(),
            ]);
    }
}
