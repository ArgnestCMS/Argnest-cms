<?php

namespace App\Filament\Resources\SystemBackups;

use App\Filament\Resources\SystemBackups\Pages\ListSystemBackups;
use App\Filament\Resources\SystemBackups\Tables\SystemBackupsTable;
use App\Models\SystemBackup;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class SystemBackupResource extends Resource
{
    protected static ?string $model = SystemBackup::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArchiveBoxArrowDown;

    protected static string|UnitEnum|null $navigationGroup = 'Yonetim';

    protected static ?string $navigationLabel = 'Sistem Yedekleri';

    protected static ?string $modelLabel = 'Sistem Yedegi';

    protected static ?string $pluralModelLabel = 'Sistem Yedekleri';

    public static function shouldRegisterNavigation(): bool
    {
        return static::canViewAny();
    }

    public static function canViewAny(): bool
    {
        return static::userCanAny(['backup_manage', 'admin_manage']);
    }

    public static function canView($record): bool
    {
        return static::canViewAny();
    }

    public static function canCreate(): bool
    {
        return static::userCanAny(['backup_create', 'backup_manage', 'admin_manage']);
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return static::userCanAny(['backup_delete', 'backup_manage', 'admin_manage']);
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }

    public static function canDownload(): bool
    {
        return static::userCanAny(['backup_download', 'backup_manage', 'admin_manage']);
    }

    public static function table(Table $table): Table
    {
        return SystemBackupsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSystemBackups::route('/'),
        ];
    }

    private static function userCanAny(array $permissions): bool
    {
        $user = auth()->user();

        return $user instanceof User
            && collect($permissions)->contains(fn (string $permission): bool => $user->hasPermission($permission));
    }
}
