<?php

namespace lion9966\utils\middleware;

use lion9966\utils\exception\ResponseCode;
use lion9966\utils\Token;
use lion9966\utils\exception\ResponseJson;
use think\exception\HttpException;
use lion9966\utils\permission\model\User;

class Permission
{
    use ResponseJson;

    public function handle($request, \Closure $next)
    {
        $requestUrl = request()->rule()->getName() ?? '';
        if (!$this->shouldPassThrough()) {
            try {
                $this->permissionCheck();
            } catch (HttpException $e) {
                return $this->error($e->getMessage(), $e->getStatusCode());
            }
        }
        return $next($request);
    }

    /**
     * 权限检测
     */
    public function permissionCheck()
    {
        try {
            $userId = (new Token())->withRequestToken()->getClaim('id') ?? '';
        } catch (\Exception $e) {
            throw new HttpException(ResponseCode::AUTH_ERROR, $e->getMessage());
        }
        $requestUrl = request()->rule()->getName() ?? '';
        $user       = User::find($userId);
        if (!$user->can($requestUrl)) {
            throw new HttpException(ResponseCode::AUTH_ERROR, $this->lang('Do not have permission'));
        }
    }

    /**
     * 例外
     * @return bool
     */
    protected function shouldPassThrough(): bool
    {
        if (in_array(request()->rule()->getName(), config('auth.permission_filter')) || app('user-data')->isRoot()) {
            return true;
        }
        return false;
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
            Lang::load([__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR . $getLangSet . DIRECTORY_SEPARATOR . 'permission.php']);
        }
        return Lang::get($name);
    }

}
