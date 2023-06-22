<?php

namespace lion9966\utils\permission\model;

use think\Model;
use lion9966\utils\permission\contract\PermissionContract;

/**
 * 权限
 */
class Permission extends Model implements PermissionContract
{
    use \lion9966\utils\permission\traits\Permission;
}
