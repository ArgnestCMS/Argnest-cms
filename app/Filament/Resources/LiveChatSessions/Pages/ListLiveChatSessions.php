<?php

namespace App\Filament\Resources\LiveChatSessions\Pages;

use App\Filament\Resources\LiveChatSessions\LiveChatSessionResource;
use Filament\Resources\Pages\ListRecords;

class ListLiveChatSessions extends ListRecords
{
    protected static string $resource = LiveChatSessionResource::class;
}
