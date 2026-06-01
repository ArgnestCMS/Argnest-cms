<?php

namespace App\Filament\Resources\LiveChatSessions;

use App\Filament\Resources\LiveChatSessions\Pages\EditLiveChatSession;
use App\Filament\Resources\LiveChatSessions\Pages\ListLiveChatSessions;
use App\Filament\Resources\LiveChatSessions\Schemas\LiveChatSessionForm;
use App\Filament\Resources\LiveChatSessions\Tables\LiveChatSessionsTable;
use App\Models\LiveChatSession;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class LiveChatSessionResource extends Resource
{
    protected static ?string $model = LiveChatSession::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static string|UnitEnum|null $navigationGroup = 'Destek';

    protected static ?string $navigationLabel = 'Canli Destek';

    protected static ?string $modelLabel = 'Canli Destek Sohbeti';

    protected static ?string $pluralModelLabel = 'Canli Destek Sohbetleri';

    public static function canViewAny(): bool
    {
        $user = auth()->user();

        return $user instanceof User && $user->hasPermission('live_chat_view');
    }

    public static function canView($record): bool
    {
        return static::canViewAny();
    }

    public static function canEdit($record): bool
    {
        return static::canViewAny();
    }

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
