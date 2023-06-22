<?php

namespace lion9966\utils\data;

use lion9966\utils\permission\model\User;

class UserData
{
    protected $id = null;
    protected $name = null;
    protected $data = [];

    //设置id
    public function withId($id): UserData
    {
        $this->id = $id;
        return $this;
    }

    //获取id
    public function getId()
    {
        return $this->id;
    }

    //设置data
    public function withData($data): UserData
    {
        $this->data = $data;
        return $this;
    }

    //获取data
    public function getData()
    {
        return $this->data;
    }

    //是否为顶级账号
    public function isRoot()
    {
        return ((int)$this->id === config('permission.super_id'));
    }

    //判断是否有权限
    public function hasPermissions($rule)
    {
        $user = User::where(['id' => $this->id, 'status' => 1])->find();
        if (!empty($user)) {
            if ($this->isRoot()) {
                return true;
            } else {
                if ($user->can($rule)) {
                    return true;
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
    }

    //判断是否有角色
    public function isRole()
    {
        if (empty($this->data['roles']) && !$this->isRoot()) {
            return false;
        }
        return true;
    }
}
