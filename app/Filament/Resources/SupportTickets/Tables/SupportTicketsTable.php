<?php

namespace App\Filament\Resources\SupportTickets\Tables;

use App\Models\SupportTicket;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SupportTicketsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('ticket_no')
                    ->label('Ticket No')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('customer.name')
                    ->label('Musteri')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('subject')
                    ->label('Konu')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Durum')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => SupportTicket::statusOptions()[$state] ?? $state)
                    ->color(fn (string $state): string => SupportTicket::statusColors()[$state] ?? 'gray')
                    ->sortable(),
                TextColumn::make('priority')
                    ->label('Oncelik')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => SupportTicket::priorityOptions()[$state] ?? $state)
                    ->color(fn (string $state): string => SupportTicket::priorityColors()[$state] ?? 'gray')
                    ->sortable(),
                TextColumn::make('latest_message_at')
                    ->label('Son mesaj tarihi')
                    ->getStateUsing(fn (SupportTicket $record) => $record->messages()->latest('created_at')->value('created_at') ?: $record->updated_at)
                    ->dateTime('d.m.Y H:i', timezone: 'Europe/Istanbul')
                    ->sortable(query: fn ($query, string $direction) => $query->orderBy('updated_at', $direction)),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Durum')
                    ->options(SupportTicket::statusOptions()),
                SelectFilter::make('priority')
                    ->label('Oncelik')
                    ->options(SupportTicket::priorityOptions()),
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
