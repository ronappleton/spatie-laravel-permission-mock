<?php

declare(strict_types=1);

namespace Appleton\SpatieLaravelPermissionMock\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\Permission\Models\Role as BaseRole;

class RoleUuid extends BaseRole
{
    use HasUuids;

    protected $guard_name = 'api';

    protected $table = 'roles';
}