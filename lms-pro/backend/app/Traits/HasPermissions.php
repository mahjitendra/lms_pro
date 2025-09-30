<?php

namespace App\Traits;

use App\Models\User\Permission;

/**
 * This trait is largely for clarity and can be expanded.
 * The core logic is included in the HasRoles trait for simplicity in this example,
 * but in a larger application, you might separate the concerns more cleanly.
 */
trait HasPermissions
{
    /**
     * Grant the given permission(s) to the user.
     *
     * @param  string|array|Permission  ...$permissions
     * @return $this
     */
    public function givePermissionTo(...$permissions)
    {
        $permissions = collect($permissions)
            ->flatten()
            ->map(function ($permission) {
                if (is_string($permission)) {
                    return Permission::findByName($permission, 'web');
                }
                return $permission;
            })
            ->all();

        $this->permissions()->saveMany($permissions);

        return $this;
    }

    /**
     * Revoke the given permission(s) from the user.
     *
     * @param  string|Permission  $permission
     */
    public function revokePermissionTo($permission)
    {
        if (is_string($permission)) {
            $permission = Permission::findByName($permission, 'web');
        }

        $this->permissions()->detach($permission);
    }
}