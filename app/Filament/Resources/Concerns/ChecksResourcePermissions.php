<?php

namespace App\Filament\Resources\Concerns;

use App\Models\User;

trait ChecksResourcePermissions
{
    public static function shouldRegisterNavigation(): bool
    {
        return static::canViewAny();
    }

    public static function canViewAny(): bool
    {
        return static::userCan(static::permission('viewPermission'));
    }

    public static function canView($record): bool
    {
        return static::canViewAny();
    }

    public static function canCreate(): bool
    {
        return static::userCan(static::permission('createPermission') ?? static::permission('editPermission') ?? static::permission('viewPermission'));
    }

    public static function canEdit($record): bool
    {
        return static::userCan(static::permission('editPermission') ?? static::permission('viewPermission'));
    }

    public static function canDelete($record): bool
    {
        return static::userCan(static::permission('deletePermission') ?? static::permission('editPermission') ?? static::permission('viewPermission'));
    }

    public static function canDeleteAny(): bool
    {
        return static::userCan(static::permission('deletePermission') ?? static::permission('editPermission') ?? static::permission('viewPermission'));
    }

    protected static function permission(string $property): ?string
    {
        return property_exists(static::class, $property) ? static::$$property : null;
    }

    protected static function userCan(?string $permission): bool
    {
        $user = auth()->user();

        return $permission !== null
            && $user instanceof User
            && (
                $user->hasPermission($permission)
                || $user->hasPermission('admin_manage')
            );
    }
}
