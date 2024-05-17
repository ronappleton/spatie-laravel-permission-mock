<?php

declare(strict_types=1);

namespace Tests\Feature;

use Appleton\SpatieLaravelPermissionMock\Models\PermissionUuid;
use Appleton\SpatieLaravelPermissionMock\Models\RoleUuid;
use Appleton\SpatieLaravelPermissionMock\Models\UserUuid;
use Appleton\SpatieLaravelPermissionMock\ServiceProvider;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\TestCase;

#[CoversClass(UserUuid::class)]
#[CoversClass(ServiceProvider::class)]
#[CoversClass(PermissionUuid::class)]
#[CoversClass(RoleUuid::class)]
class MockPermissionsUuidTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('mock-permissions.uuids', true);

        $this->artisan('migrate:fresh', ['--database' => 'testing']);
    }

    public function testCanCreateUser(): void
    {
        $user = UserUuid::factory()->create();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ]);

        $this->assertIsString($user->id);
    }

    public function testCanCreateRole(): void
    {
        $role = RoleUuid::create(['name' => 'admin']);

        $this->assertDatabaseHas('roles', [
            'id' => $role->id,
            'name' => $role->name,
        ]);

        $this->assertIsString($role->id);
    }

    public function testCanCreatePermission(): void
    {
        $permission = PermissionUuid::create(['name' => 'edit articles']);

        $this->assertDatabaseHas('permissions', [
            'id' => $permission->id,
            'name' => $permission->name,
        ]);

        $this->assertIsString($permission->id);
    }

    public function testCanAssignRoleToUser(): void
    {
        $user = UserUuid::factory()->create();
        $role = RoleUuid::create(['name' => 'admin']);

        $user->assignRole($role);

        $this->assertDatabaseHas('model_has_roles', [
            'role_id' => $role->id,
            'model_id' => $user->id,
            'model_type' => UserUuid::class,
        ]);

        $this->assertIsString($role->id);
    }

    public function testCanAssignPermissionToRole(): void
    {
        $role = RoleUuid::create(['name' => 'admin']);
        $permission = PermissionUuid::create(['name' => 'edit articles']);

        $role->givePermissionTo($permission);

        $this->assertDatabaseHas('role_has_permissions', [
            'role_id' => $role->id,
            'permission_id' => $permission->id,
        ]);

        $this->assertIsString($role->id);
        $this->assertIsString($permission->id);
    }

    public function testCanAssignPermissionToUser(): void
    {
        $user = UserUuid::factory()->create();
        $permission = PermissionUuid::create(['name' => 'edit articles']);

        $user->givePermissionTo($permission);

        $this->assertDatabaseHas('model_has_permissions', [
            'permission_id' => $permission->id,
            'model_id' => $user->id,
            'model_type' => UserUuid::class,
        ]);

        $this->assertIsString($permission->id);
    }

    public function testCanUseUserToAuthenticateARequest(): void
    {
        $user = UserUuid::factory()->create();

        Route::get('test-route', function () {
            return response()->json(['message' => 'success']);
        })
            ->middleware('auth:api')
            ->name('test-route');

        config()->set([
            'auth.guards.api.driver' => 'session',
            'auth.guards.api.provider' => 'users',
            'auth.providers.users.driver' => 'eloquent',
            'auth.providers.users.model' => UserUuid::class,
        ]);

        $response = $this->actingAs($user)->json('get', route('test-route'));

        $response->assertStatus(200);
    }
}