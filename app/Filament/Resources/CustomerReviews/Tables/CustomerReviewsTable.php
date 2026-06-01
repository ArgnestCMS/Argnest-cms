<?php

namespace App\Filament\Resources\CustomerReviews\Tables;

use App\Models\CustomerReview;
use App\Models\CustomerNotification;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CustomerReviewsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('customer.name')
                    ->label('Musteri')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('rating')
                    ->label('Puan')
                    ->formatStateUsing(fn (?int $state): string => $state ? $state . ' / 5' : '-')
                    ->sortable(),
                TextColumn::make('title')
                    ->label('Baslik')
                    ->searchable()
                    ->limit(40),
                TextColumn::make('status')
                    ->label('Durum')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => CustomerReview::statusOptions()[$state] ?? $state)
                    ->color(fn (string $state): string => CustomerReview::statusColors()[$state] ?? 'gray')
                    ->sortable(),
                IconColumn::make('hide_name')
                    ->label('Isim Gizli')
                    ->boolean(),
                IconColumn::make('hide_contact')
                    ->label('Iletisim Gizli')
                    ->boolean(),
                TextColumn::make('approved_at')
                    ->label('Onay Tarihi')
                    ->dateTime('d.m.Y H:i', timezone: 'Europe/Istanbul')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Gonderim Tarihi')
                    ->dateTime('d.m.Y H:i', timezone: 'Europe/Istanbul')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Durum')
                    ->options(CustomerReview::statusOptions()),
                SelectFilter::make('rating')
                    ->label('Puan')
                    ->options([
                        1 => '1 / 5',
                        2 => '2 / 5',
                        3 => '3 / 5',
                        4 => '4 / 5',
                        5 => '5 / 5',
                    ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                Action::make('approve')
                    ->label('Onayla')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (CustomerReview $record): bool => $record->status !== CustomerReview::STATUS_APPROVED)
                    ->action(function (CustomerReview $record): void {
                        $record->forceFill([
                            'status' => CustomerReview::STATUS_APPROVED,
                            'approved_at' => now(),
                        ])->save();

                        CustomerNotification::query()->create([
                            'user_id' => $record->user_id,
                            'title' => 'Yorumunuz onaylandi',
                            'message' => 'Musteri yorumunuz onaylandi ve yayina alindi.',
                            'type' => 'review',
                            'link' => route('frontend.customer.reviews.index'),
                        ]);
                    }),
                Action::make('reject')
                    ->label('Reddet')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (CustomerReview $record): bool => $record->status !== CustomerReview::STATUS_REJECTED)
                    ->action(fn (CustomerReview $record): bool => $record->forceFill([
                        'status' => CustomerReview::STATUS_REJECTED,
                        'approved_at' => null,
                    ])->save()),
                EditAction::make()
                    ->label('Duzenle'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Secilenleri Sil'),
                ]),
            ]);
    }
}
