<?php

namespace lion9966\utils\permission\model;

use think\Model;
use lion9966\utils\permission\contract\RoleContract;

/**
 * 角色
 */
class Role extends Model implements RoleContract
{
    use \lion9966\utils\permission\traits\Role;
}
