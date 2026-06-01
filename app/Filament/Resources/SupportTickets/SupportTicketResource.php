<?php

namespace App\Filament\Resources\SupportTickets;

use App\Filament\Resources\Concerns\ChecksResourcePermissions;
use App\Filament\Resources\SupportTickets\Pages\CreateSupportTicket;
use App\Filament\Resources\SupportTickets\Pages\EditSupportTicket;
use App\Filament\Resources\SupportTickets\Pages\ListSupportTickets;
use App\Filament\Resources\SupportTickets\Schemas\SupportTicketForm;
use App\Filament\Resources\SupportTickets\Tables\SupportTicketsTable;
use App\Models\SupportTicket;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class SupportTicketResource extends Resource
{
    use ChecksResourcePermissions;

    protected static ?string $model = SupportTicket::class;

    protected static ?string $viewPermission = 'support_view';

    protected static ?string $createPermission = 'support_reply';

    protected static ?string $editPermission = 'support_view';

    protected static ?string $deletePermission = 'support_reply';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedLifebuoy;

    protected static string|UnitEnum|null $navigationGroup = 'Musteri Yonetimi';

    protected static ?string $navigationLabel = 'Destek Biletleri';

    protected static ?string $modelLabel = 'Destek Bileti';

    protected static ?string $pluralModelLabel = 'Destek Biletleri';

    protected static ?string $recordTitleAttribute = 'ticket_no';

    public static function form(Schema $schema): Schema
    {
        return SupportTicketForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SupportTicketsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSupportTickets::route('/'),
            'create' => CreateSupportTicket::route('/create'),
            'edit' => EditSupportTicket::route('/{record}/edit'),
        ];
    }
}
