<?php

declare(strict_types=1);

namespace Appleton\SpatieLaravelPermissionMock\Models;

use Spatie\Permission\Models\Permission as BasePermission;

class Permission extends BasePermission
{
    protected $guard_name = 'api';
}