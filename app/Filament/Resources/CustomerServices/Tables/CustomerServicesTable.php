<?php

namespace App\Filament\Resources\CustomerServices\Tables;

use App\Models\CustomerService;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CustomerServicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('customer.name')
                    ->label('Musteri')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('service_name')
                    ->label('Hizmet adi')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('domain_name')
                    ->label('Domain')
                    ->searchable(),
                TextColumn::make('hosting_package')
                    ->label('Hosting')
                    ->searchable(),
                TextColumn::make('server_ip')
                    ->label('Sunucu')
                    ->searchable(),
                TextColumn::make('expiry_date')
                    ->label('Bitis Tarihi')
                    ->date('d.m.Y')
                    ->sortable(),
                TextColumn::make('days_until_expiry')
                    ->label('Kalan Gun')
                    ->getStateUsing(fn (CustomerService $record): string => match (true) {
                        $record->daysUntilExpiry() === null => '-',
                        $record->daysUntilExpiry() < 0 => abs($record->daysUntilExpiry()) . ' gun gecmis',
                        $record->daysUntilExpiry() === 0 => 'Bugun',
                        default => $record->daysUntilExpiry() . ' gun',
                    })
                    ->badge()
                    ->color(fn (CustomerService $record): string => CustomerService::renewalStatusColors()[$record->renewalStatus()] ?? 'gray')
                    ->sortable(query: fn (Builder $query, string $direction): Builder => $query->orderBy('expiry_date', $direction)),
                TextColumn::make('renewal_status')
                    ->label('Yenileme Durumu')
                    ->getStateUsing(fn (CustomerService $record): string => $record->renewalStatus())
                    ->formatStateUsing(fn (string $state): string => CustomerService::renewalStatusOptions()[$state] ?? $state)
                    ->badge()
                    ->color(fn (string $state): string => CustomerService::renewalStatusColors()[$state] ?? 'gray'),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                Filter::make('expired')
                    ->label('Suresi Gecenler')
                    ->query(fn (Builder $query): Builder => $query
                        ->whereNotNull('expiry_date')
                        ->whereDate('expiry_date', '<', now()->toDateString())),
                Filter::make('expires_in_30_days')
                    ->label('30 Gun Icinde Bitecekler')
                    ->query(fn (Builder $query): Builder => $query
                        ->whereNotNull('expiry_date')
                        ->whereDate('expiry_date', '>=', now()->toDateString())
                        ->whereDate('expiry_date', '<=', now()->copy()->addDays(30)->toDateString())),
                Filter::make('expires_in_90_days')
                    ->label('90 Gun Icinde Bitecekler')
                    ->query(fn (Builder $query): Builder => $query
                        ->whereNotNull('expiry_date')
                        ->whereDate('expiry_date', '>=', now()->toDateString())
                        ->whereDate('expiry_date', '<=', now()->copy()->addDays(90)->toDateString())),
                TernaryFilter::make('is_active')
                    ->label('Aktif Hizmetler')
                    ->trueLabel('Aktif Hizmetler')
                    ->falseLabel('Pasif Hizmetler')
                    ->placeholder('Tum Hizmetler'),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
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
