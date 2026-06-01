<?php

namespace App\Filament\Resources\AdminActivityLogs\Tables;

use App\Models\AdminActivityLog;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AdminActivityLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('admin.name')
                    ->label('Admin Kullanici')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('action')
                    ->label('Islem')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => AdminActivityLog::actionOptions()[$state] ?? $state)
                    ->sortable(),
                TextColumn::make('description')
                    ->label('Aciklama')
                    ->searchable()
                    ->limit(80),
                TextColumn::make('ip_address')
                    ->label('IP')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user_agent')
                    ->label('User Agent')
                    ->limit(60)
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Tarih')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('user_id')
                    ->label('Admin Kullanici')
                    ->options(fn (): array => User::query()
                        ->where('role', User::ROLE_ADMIN)
                        ->orderBy('name')
                        ->pluck('name', 'id')
                        ->all())
                    ->searchable(),
                SelectFilter::make('action')
                    ->label('Islem Turu')
                    ->options(AdminActivityLog::actionOptions()),
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
