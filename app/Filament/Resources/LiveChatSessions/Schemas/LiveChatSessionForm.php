<?php

namespace App\Filament\Resources\LiveChatSessions\Schemas;

use App\Models\LiveChatMessage;
use App\Models\LiveChatSession;
use App\Models\User;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class LiveChatSessionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Ziyaretci Bilgileri')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('visitor_name')
                                    ->label('Ad Soyad')
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->maxLength(255),
                                TextInput::make('visitor_email')
                                    ->label('E-posta')
                                    ->email()
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->maxLength(255),
                                TextInput::make('visitor_phone')
                                    ->label('Telefon')
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->maxLength(255),
                                Select::make('status')
                                    ->label('Durum')
                                    ->options(LiveChatSession::statusOptions())
                                    ->disabled(fn (): bool => ! (auth()->user()?->hasPermission('live_chat_close') ?? false))
                                    ->required(),
                                Select::make('assigned_user_id')
                                    ->label('Atanan Admin')
                                    ->relationship(
                                        'assignedUser',
                                        'name',
                                        fn ($query) => $query
                                            ->where('role', User::ROLE_ADMIN)
                                            ->orderBy('name'),
                                    )
                                    ->searchable()
                                    ->disabled(fn (): bool => ! (auth()->user()?->hasPermission('live_chat_reply') ?? false))
                                    ->preload(),
                                TextInput::make('ip_address')
                                    ->label('IP Adresi')
                                    ->disabled()
                                    ->dehydrated(false),
                            ]),
                    ])
                    ->columnSpanFull(),
                Section::make('Mesaj Gecmisi')
                    ->schema([
                        Placeholder::make('messages')
                            ->label('')
                            ->content(function (?LiveChatSession $record): HtmlString {
                                $messages = $record?->messages()
                                    ->with('sender')
                                    ->oldest('created_at')
                                    ->get() ?? collect();

                                if ($messages->isEmpty()) {
                                    return new HtmlString('Bu sohbet icin henuz mesaj yok.');
                                }

                                return new HtmlString($messages
                                    ->map(function (LiveChatMessage $message) use ($record): string {
                                        $sender = match ($message->sender_type) {
                                            LiveChatMessage::SENDER_ADMIN => 'Admin: ' . e($message->sender?->name ?: 'Destek Ekibi'),
                                            LiveChatMessage::SENDER_SYSTEM => 'Sistem',
                                            default => 'Ziyaretci: ' . e($record?->visitor_name ?: 'Isimsiz'),
                                        };
                                        $date = e($message->created_at?->timezone('Europe/Istanbul')->format('d.m.Y H:i') ?: '');
                                        $body = nl2br(e($message->message));
                                        $isSystem = $message->sender_type === LiveChatMessage::SENDER_SYSTEM;
                                        $cardStyle = $isSystem
                                            ? 'border:1px solid #fde68a;border-radius:14px;padding:14px;margin-bottom:12px;background:#fffbeb;color:#78350f;'
                                            : 'border:1px solid #e2e8f0;border-radius:14px;padding:14px;margin-bottom:12px;background:#f8fafc;color:#334155;';
                                        $messageStyle = $isSystem
                                            ? 'margin-top:8px;color:#78350f;'
                                            : 'margin-top:8px;color:#334155;';

                                        return '<div style="' . $cardStyle . '">'
                                            . '<strong style="color:#1e293b;font-weight:600;">' . $sender . '</strong> <span style="color:#64748b;">' . $date . '</span>'
                                            . '<div style="' . $messageStyle . '">' . $body . '</div>'
                                            . '</div>';
                                    })
                                    ->implode(''));
                            }),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
