<?php

namespace App\Http\Controllers\Tenant;

use App\Enums\Tenant\PermissionNameEnum;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

abstract class BaseTenantController extends Controller
{
    /**
     * 檢查權限
     */
    protected function authorizePermission(PermissionNameEnum $permission): void
    {
        Gate::forUser(auth()->user())->authorize($permission->value);
    }

    /**
     * 檢查使用者是否有特定權限
     */
    protected function hasPermission(PermissionNameEnum $permission): bool
    {
        return auth()->user()->can($permission->value);
    }

    /**
     * 檢查使用者是否有特定角色
     */
    protected function hasRole(string $role): bool
    {
        return auth()->user()->hasRole($role);
    }
}
