<?php

declare(strict_types=1);

namespace Appleton\SpatieLaravelPermissionMock\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Auth\Authenticatable;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

/**
 * @mixin User
 *
 * @PROPERTY string $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $remember_token
 * @property string $created_at
 * @property string $updated_at
 */
class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable;
    use Authorizable;
    use HasRoles;
    use HasPermissions;
    use HasFactory;
    use Notifiable;

    protected $guard_name = 'api';

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }
}
