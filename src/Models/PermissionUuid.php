<?php

declare(strict_types=1);

namespace Appleton\SpatieLaravelPermissionMock\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\Permission\Models\Permission as BasePermission;

class PermissionUuid extends BasePermission
{
    use HasUuids;

    protected $guard_name = 'api';

    protected $table = 'permissions';
}