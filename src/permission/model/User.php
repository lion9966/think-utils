<?php

namespace lion9966\utils\permission\model;

use think\Model;
use lion9966\utils\permission\contract\UserContract;

/**
 * 用户
 */
class User extends Model implements UserContract
{
    use \lion9966\utils\permission\traits\User;
}
