<?php

namespace App\Filament\Resources\CustomerActivityLogs\Tables;

use App\Models\CustomerActivityLog;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CustomerActivityLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('customer.name')
                    ->label('Musteri')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('action')
                    ->label('Islem')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => CustomerActivityLog::actionOptions()[$state] ?? $state)
                    ->sortable(),
                TextColumn::make('description')
                    ->label('Aciklama')
                    ->searchable()
                    ->limit(70),
                TextColumn::make('ip_address')
                    ->label('IP')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('user_agent')
                    ->label('User Agent')
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Tarih')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('user_id')
                    ->label('Kullanici')
                    ->options(fn (): array => User::query()
                        ->where('role', User::ROLE_CUSTOMER)
                        ->orderBy('name')
                        ->pluck('name', 'id')
                        ->all())
                    ->searchable(),
                SelectFilter::make('action')
                    ->label('Islem Turu')
                    ->options(CustomerActivityLog::actionOptions()),
                Filter::make('created_at')
                    ->label('Tarih')
                    ->schema([
                        DatePicker::make('created_from')
                            ->label('Baslangic'),
                        DatePicker::make('created_until')
                            ->label('Bitis'),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => $query
                        ->when($data['created_from'] ?? null, fn (Builder $query, string $date): Builder => $query->whereDate('created_at', '>=', $date))
                        ->when($data['created_until'] ?? null, fn (Builder $query, string $date): Builder => $query->whereDate('created_at', '<=', $date))),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
