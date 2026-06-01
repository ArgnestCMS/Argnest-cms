<?php

namespace App\Filament\Resources\SupportTickets\Pages;

use App\Filament\Resources\SupportTickets\SupportTicketResource;
use App\Filament\Resources\SupportTickets\Widgets\SupportTicketStatsOverview;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSupportTickets extends ListRecords
{
    protected static string $resource = SupportTicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Yeni Bilet'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            SupportTicketStatsOverview::class,
        ];
    }
}
