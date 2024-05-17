<?php

declare(strict_types=1);

namespace Appleton\SpatieLaravelPermissionMock;

use Appleton\SpatieLaravelPermissionMock\Models\Permission;
use Appleton\SpatieLaravelPermissionMock\Models\Role;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Appleton\SpatieLaravelPermissionMock\Models\User;

class ServiceProvider extends BaseServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        config()->set([
            'mock-permissions.uuids' => false,
            'mock-permissions.teams' => false,
        ]);

        config()->set([
            'permission.models.permission' => Permission::class,
            'permission.models.role' => Role::class,
            'permission.cache.key' => 'spatie.permission.cache',
            'permission.table_names.roles' => 'roles',
            'permission.table_names.permissions' => 'permissions',
            'permission.table_names.model_has_permissions' => 'model_has_permissions',
            'permission.table_names.model_has_roles' => 'model_has_roles',
            'permission.table_names.role_has_permissions' => 'role_has_permissions',
        ]);

        config()->set([
            'auth.defaults.guard' => 'api',
        ]);

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}