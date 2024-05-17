<?php

declare(strict_types=1);

namespace Appleton\SpatieLaravelPermissionMock\Models;

use Spatie\Permission\Models\Role as BaseRole;

class Role extends BaseRole
{
    protected $guard_name = 'api';
}