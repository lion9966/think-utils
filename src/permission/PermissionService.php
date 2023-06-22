<?php

namespace lion9966\utils\permission;

use lion9966\utils\permission\command\Install as PermissionInstall;
use lion9966\utils\permission\middleware\Permission;
use lion9966\utils\permission\middleware\Role;

/**
 * 权限服务
 */
class PermissionService extends \think\Service
{
    public function register()
    {
        $this->app->bind('auth', Permission::class);
        $this->app->bind('auth.permission', Permission::class);
        $this->app->bind('auth.role', Role::class);
    }

    public function boot()
    {
        $this->commands([
            PermissionInstall::class,
        ]);
    }
}
