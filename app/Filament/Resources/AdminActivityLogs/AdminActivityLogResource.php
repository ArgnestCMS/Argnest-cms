<?php

namespace App\Filament\Resources\AdminActivityLogs;

use App\Filament\Resources\AdminActivityLogs\Pages\ListAdminActivityLogs;
use App\Filament\Resources\AdminActivityLogs\Tables\AdminActivityLogsTable;
use App\Models\AdminActivityLog;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class AdminActivityLogResource extends Resource
{
    protected static ?string $model = AdminActivityLog::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldCheck;

    protected static string|UnitEnum|null $navigationGroup = 'Yonetim';

    protected static ?string $navigationLabel = 'Guvenlik Loglari';

    protected static ?string $modelLabel = 'Guvenlik Logu';

    protected static ?string $pluralModelLabel = 'Guvenlik Loglari';

    public static function shouldRegisterNavigation(): bool
    {
        return static::canViewAny();
    }

    public static function canViewAny(): bool
    {
        $user = auth()->user();

        return $user instanceof User
            && ($user->hasPermission('security_logs_view') || $user->hasPermission('admin_manage'));
    }

    public static function canView($record): bool
    {
        return static::canViewAny();
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return AdminActivityLogsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAdminActivityLogs::route('/'),
        ];
    }
}
