<?php

namespace App\Filament\Resources\AdminUsers;

use App\Filament\Resources\AdminUsers\Pages\CreateAdminUser;
use App\Filament\Resources\AdminUsers\Pages\EditAdminUser;
use App\Filament\Resources\AdminUsers\Pages\ListAdminUsers;
use App\Filament\Resources\AdminUsers\Schemas\AdminUserForm;
use App\Filament\Resources\AdminUsers\Tables\AdminUsersTable;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class AdminUserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldCheck;

    protected static string|UnitEnum|null $navigationGroup = 'Yönetim';

    protected static ?string $navigationLabel = 'Admin Kullanıcıları';

    protected static ?string $modelLabel = 'Admin Kullanıcı';

    protected static ?string $pluralModelLabel = 'Admin Kullanıcıları';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('role', User::ROLE_ADMIN);
    }

    public static function getDeleteBlockMessage(User $record): ?string
    {
        if ($record->role !== User::ROLE_ADMIN) {
            return 'Sadece admin kullanıcılar silinebilir.';
        }

        if ($record->id === auth()->id()) {
            return 'Kendi hesabınızı silemezsiniz.';
        }

        $adminCount = User::query()
            ->where('role', User::ROLE_ADMIN)
            ->count();

        if ($adminCount <= 1) {
            return 'Sistemde en az bir admin kullanicisi kalmalidir.';
        }

        $activeAdminCount = User::query()
            ->where('role', User::ROLE_ADMIN)
            ->where('is_active', true)
            ->count();

        if ($record->is_active && $activeAdminCount <= 1) {
            return 'Sistemde en az bir aktif admin kalmalıdır.';
        }

        return null;
    }

    public static function getDeactivateBlockMessage(User $record): ?string
    {
        if ($record->role !== User::ROLE_ADMIN || ! $record->is_active) {
            return null;
        }

        if ($record->id === auth()->id()) {
            return 'Kendi hesabinizi pasif yapamazsiniz.';
        }

        $activeAdminCount = User::query()
            ->where('role', User::ROLE_ADMIN)
            ->where('is_active', true)
            ->count();

        if ($activeAdminCount <= 1) {
            return 'Sistemde en az bir aktif admin kalmalidir.';
        }

        return null;
    }

    public static function form(Schema $schema): Schema
    {
        return AdminUserForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AdminUsersTable::configure($table);
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
            'index' => ListAdminUsers::route('/'),
            'create' => CreateAdminUser::route('/create'),
            'edit' => EditAdminUser::route('/{record}/edit'),
        ];
    }
}
