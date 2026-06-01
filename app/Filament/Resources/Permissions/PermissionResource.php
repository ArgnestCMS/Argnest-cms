<?php

namespace App\Filament\Resources\Permissions;

use App\Filament\Resources\Concerns\ChecksResourcePermissions;
use App\Filament\Resources\Permissions\Pages\CreatePermission;
use App\Filament\Resources\Permissions\Pages\EditPermission;
use App\Filament\Resources\Permissions\Pages\ListPermissions;
use App\Filament\Resources\Permissions\Schemas\PermissionForm;
use App\Filament\Resources\Permissions\Tables\PermissionsTable;
use App\Models\Permission;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PermissionResource extends Resource
{
    use ChecksResourcePermissions;

    protected static ?string $model = Permission::class;

    protected static ?string $viewPermission = 'permission_manage';

    protected static ?string $editPermission = 'permission_manage';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedKey;

    protected static string|UnitEnum|null $navigationGroup = 'Yonetim';

    protected static ?string $navigationLabel = 'Yetkiler';

    protected static ?string $modelLabel = 'Yetki';

    protected static ?string $pluralModelLabel = 'Yetkiler';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return PermissionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PermissionsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPermissions::route('/'),
            'create' => CreatePermission::route('/create'),
            'edit' => EditPermission::route('/{record}/edit'),
        ];
    }
}
