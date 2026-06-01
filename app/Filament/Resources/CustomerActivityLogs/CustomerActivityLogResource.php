<?php

namespace App\Filament\Resources\CustomerActivityLogs;

use App\Filament\Resources\Concerns\ChecksResourcePermissions;
use App\Filament\Resources\CustomerActivityLogs\Pages\ListCustomerActivityLogs;
use App\Filament\Resources\CustomerActivityLogs\Tables\CustomerActivityLogsTable;
use App\Models\CustomerActivityLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class CustomerActivityLogResource extends Resource
{
    use ChecksResourcePermissions;

    protected static ?string $model = CustomerActivityLog::class;

    protected static ?string $viewPermission = 'customer_view';

    protected static ?string $editPermission = 'customer_edit';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;

    protected static string|UnitEnum|null $navigationGroup = 'Musteri Yonetimi';

    protected static ?string $navigationLabel = 'Musteri Aktiviteleri';

    protected static ?string $modelLabel = 'Musteri Aktivitesi';

    protected static ?string $pluralModelLabel = 'Musteri Aktiviteleri';

    public static function table(Table $table): Table
    {
        return CustomerActivityLogsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCustomerActivityLogs::route('/'),
        ];
    }
}
