<?php

namespace App\Enums\Tenant;

enum PermissionNameEnum: string
{
    case 所有權限       = 'manage';

    /**
     * 設定
     */
    case 設定介面       = 'settings.interface';
    case 員工管理       = 'users';
    case 角色管理       = 'roles';

    public function label(): string
    {
        return match($this) {
            self::所有權限 => '所有權限',
            self::設定介面 => '設定介面',
            self::員工管理 => '員工管理',
            self::角色管理 => '角色管理',
        };
    }

    public static function getDisplayName(string $key): string
    {
        $permission = self::tryFrom($key);
        return $permission ? $permission->label() : $key;
    }
}
