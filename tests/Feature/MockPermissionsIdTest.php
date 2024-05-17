<?php

declare(strict_types=1);

namespace Feature;

use Appleton\SpatieLaravelPermissionMock\Models\Permission;
use Appleton\SpatieLaravelPermissionMock\Models\Role;
use Appleton\SpatieLaravelPermissionMock\Models\User;
use Appleton\SpatieLaravelPermissionMock\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\TestCase;

#[CoversClass(User::class)]
#[CoversClass(ServiceProvider::class)]
#[CoversClass(Permission::class)]
#[CoversClass(Role::class)]
class MockPermissionsIdTest extends TestCase
{
    public function testCanCreateUser(): void
    {
        $user = User::factory()->create();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ]);

        $this->assertIsInt($user->id);
    }

    public function testCanCreateRole(): void
    {
        $role = Role::create(['name' => 'admin']);

        $this->assertDatabaseHas('roles', [
            'id' => $role->id,
            'name' => $role->name,
        ]);

        $this->assertIsInt($role->id);
    }

    public function testCanCreatePermission(): void
    {
        $permission = Permission::create(['name' => 'edit articles']);

        $this->assertDatabaseHas('permissions', [
            'id' => $permission->id,
            'name' => $permission->name,
        ]);

        $this->assertIsInt($permission->id);
    }

    public function testCanAssignRoleToUser(): void
    {
        $user = User::factory()->create();
        $role = Role::create(['name' => 'admin']);

        $user->assignRole($role);

        $this->assertDatabaseHas('model_has_roles', [
            'role_id' => $role->id,
            'model_id' => $user->id,
            'model_type' => User::class,
        ]);

        $this->assertIsInt($role->id);
    }

    public function testCanAssignPermissionToRole(): void
    {
        $role = Role::create(['name' => 'admin']);
        $permission = Permission::create(['name' => 'edit articles']);

        $role->givePermissionTo($permission);

        $this->assertDatabaseHas('role_has_permissions', [
            'role_id' => $role->id,
            'permission_id' => $permission->id,
        ]);

        $this->assertIsInt($role->id);
        $this->assertIsInt($permission->id);
    }

    public function testCanAssignPermissionToUser(): void
    {
        $user = User::factory()->create();
        $permission = Permission::create(['name' => 'edit articles']);

        $user->givePermissionTo($permission);

        $this->assertDatabaseHas('model_has_permissions', [
            'permission_id' => $permission->id,
            'model_id' => $user->id,
            'model_type' => User::class,
        ]);

        $this->assertIsInt($permission->id);
    }

    public function testCanUseUserToAuthenticateARequest(): void
    {
        $user = User::factory()->create();

        Route::get('test-route', function () {
            return response()->json(['message' => 'success']);
        })
            ->middleware('auth:api')
            ->name('test-route');

        config()->set([
            'auth.guards.api.driver' => 'session',
            'auth.guards.api.provider' => 'users',
            'auth.providers.users.driver' => 'eloquent',
            'auth.providers.users.model' => User::class,
        ]);

        $response = $this->actingAs($user)->json('get', route('test-route'));

        $response->assertStatus(200);
    }
}