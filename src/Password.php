<?php

namespace lion9966\utils;

class Password
{
    protected $salt = '';

    /**
     * 设置盐
     * @param string $salt
     * @return \alion9966\utils\Password
     */
    public function withSalt(string $salt): Password
    {
        $this->salt = $salt;
        return $this;
    }

    /**
     * 密码加密
     * @param string $password
     * @param string $salt
     * @return array|string
     */
    public function encrypt(string $password, string $salt = '')
    {
        $pwd             = [];
        $pwd['salt']     = $salt ?: $this->randomString();
        $pwd['password'] = md5(md5($password . $pwd['salt']) . $this->salt);
        return $salt ? $pwd['password'] : $pwd;
    }

    /**
     * 随机字符串
     * @param int $len
     * @return string
     */
    protected function randomString(int $len = 6): string
    {
        $pool = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
        return substr(str_shuffle(str_repeat($pool, intval(ceil($len / strlen($pool))))), 0, $len);
    }

    /**
     * 获取密码
     * @param $password
     * @param string $salt
     * @return array|string
     */
    public function getPassword($password, string $salt = '')
    {
        return $this->withSalt(config('utils.password.salt'))->encrypt($password, $salt);
    }
}
