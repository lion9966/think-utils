<?php

namespace lion9966\utils\middleware;

use lion9966\utils\data\UserData;
use lion9966\utils\exception\handle\FailException;
use lion9966\utils\exception\ResponseCode;
use lion9966\utils\Token;
use lion9966\utils\permission\model\User;
use think\facade\Lang;

class Auth
{
    public function handle($request, \Closure $next)
    {
        if (!$this->shouldPassThrough()) {
            $this->jwtCheck();
        }
        return $next($request);
    }

    /**
     * jwt验证
     * @throws FailException
     */
    protected function jwtCheck()
    {
        try {
            $token = (new Token())->withRequestToken();
            $token->validate();
        } catch (\Exception $e) {
            throw new FailException($e->getMessage(), ResponseCode::ACCESS_TOKEN_ERROR);
        }
        $userId = $token->getClaim('user_id');

        $userInfo = User::withoutField('password,salt')->with('roles')
            ->where([['id', '=', $userId], ['status', '=', 1]])
            ->find();
        //处理角色中状态为0，不显示中间表
        foreach ($userInfo->roles as $key => $role) {
            if (!$role->status) {
                unset($userInfo->roles[$key]);
            } else {
                unset($role->pivot);
            }
        }

        if ($userInfo->isEmpty()) throw new FailException($this->lang('Account does not exist or has been disabled'), ResponseCode::AUTH_ERROR);
        //只显示角色中指定字段，并转数组
        $user = $userInfo->visible(['roles' => ['id', 'name', 'description', 'status']])->toArray();
        //绑定类
        bind('user-data', UserData::class);
        //信息初始化
        app('user-data')->withId($userId)->withData($user);
        if (!app('user-data')->isRole()) throw new FailException($this->lang('Account user group/role does not exist or has been locked'), ResponseCode::AUTH_ERROR);
    }

    /**
     * 权限例外
     * @return bool
     */
    protected function shouldPassThrough(): bool
    {
        return in_array(request()->rule()->getName(), config('auth.token_filter'));
    }

    /**
     * 语言
     * @param $name
     * @return string
     */
    protected function lang($name): string
    {
        if (!Lang::has($name)) {
            $getLangSet = Lang::getLangSet();
            Lang::load([__DIR__. DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR . $getLangSet . DIRECTORY_SEPARATOR . 'permission.php']);
        }
        return Lang::get($name);
    }

}
