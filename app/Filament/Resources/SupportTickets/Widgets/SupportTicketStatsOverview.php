<?php

namespace App\Filament\Resources\SupportTickets\Widgets;

use App\Models\SupportTicket;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SupportTicketStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Toplam destek talebi', SupportTicket::query()->count()),
            Stat::make('Acik talepler', SupportTicket::query()
                ->where('status', SupportTicket::STATUS_OPEN)
                ->count()),
            Stat::make('Bekleyen talepler', SupportTicket::query()
                ->where('status', SupportTicket::STATUS_PENDING)
                ->count()),
            Stat::make('Cozulen talepler', SupportTicket::query()
                ->where('status', SupportTicket::STATUS_CLOSED)
                ->count()),
            Stat::make('Son 30 gun talep sayisi', SupportTicket::query()
                ->where('created_at', '>=', now()->subDays(30))
                ->count()),
        ];
    }
}
