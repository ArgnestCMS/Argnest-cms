<?php

namespace App\Filament\Resources\LiveChatSessions\Tables;

use App\Models\LiveChatSession;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class LiveChatSessionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('visitor_name')
                    ->label('Ziyaretci')
                    ->placeholder('Isimsiz')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('visitor_email')
                    ->label('E-posta')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('visitor_phone')
                    ->label('Telefon')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('status')
                    ->label('Durum')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => LiveChatSession::statusOptions()[$state] ?? $state)
                    ->color(fn (string $state): string => LiveChatSession::statusColors()[$state] ?? 'gray')
                    ->sortable(),
                TextColumn::make('assignedUser.name')
                    ->label('Atanan')
                    ->placeholder('-')
                    ->sortable(),
                TextColumn::make('messages_count')
                    ->label('Mesaj')
                    ->counts('messages')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('Son Hareket')
                    ->dateTime('d.m.Y H:i', timezone: 'Europe/Istanbul')
                    ->sortable(),
                TextColumn::make('closed_at')
                    ->label('Kapanis')
                    ->dateTime('d.m.Y H:i', timezone: 'Europe/Istanbul')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Durum')
                    ->options(LiveChatSession::statusOptions()),
            ])
            ->defaultSort('updated_at', 'desc')
            ->recordActions([
                EditAction::make()
                    ->label('Yonet'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Secilenleri Sil'),
                ]),
            ]);
    }
}
