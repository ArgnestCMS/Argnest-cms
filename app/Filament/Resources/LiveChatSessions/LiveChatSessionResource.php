<?php

namespace App\Filament\Resources\LiveChatSessions;

use App\Filament\Resources\Concerns\ChecksResourcePermissions;
use App\Filament\Resources\LiveChatSessions\Pages\EditLiveChatSession;
use App\Filament\Resources\LiveChatSessions\Pages\ListLiveChatSessions;
use App\Filament\Resources\LiveChatSessions\Schemas\LiveChatSessionForm;
use App\Filament\Resources\LiveChatSessions\Tables\LiveChatSessionsTable;
use App\Models\LiveChatSession;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class LiveChatSessionResource extends Resource
{
    use ChecksResourcePermissions;

    protected static ?string $model = LiveChatSession::class;

    protected static ?string $viewPermission = 'live_chat_view';

    protected static ?string $editPermission = 'live_chat_view';

    protected static ?string $deletePermission = 'live_chat_close';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static string|UnitEnum|null $navigationGroup = 'Destek';

    protected static ?string $navigationLabel = 'Canli Destek';

    protected static ?string $modelLabel = 'Canli Destek Sohbeti';

    protected static ?string $pluralModelLabel = 'Canli Destek Sohbetleri';

    public static function form(Schema $schema): Schema
    {
        return LiveChatSessionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LiveChatSessionsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLiveChatSessions::route('/'),
            'edit' => EditLiveChatSession::route('/{record}/edit'),
        ];
    }
}
