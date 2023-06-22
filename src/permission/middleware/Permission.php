<?php

namespace lion9966\utils\permission\middleware;

use think\Request;
use think\Response;
use think\facade\Lang;
use lion9966\utils\permission\contract\PermissionMiddlewareContract;
use lion9966\utils\permission\contract\UserContract;
use lion9966\utils\exception\ResponseCode;

/**
 * 权限中间件.
 */
class Permission implements PermissionMiddlewareContract
{
    public function handle($request, \Closure $next, $permission)
    {
        if ($request->isOptions()) {
            return $next($request);
        }

        if (!$request->user) {
            return $this->handleNotLoggedIn($request);
        }

        if (false === $this->requestHasPermission($request, $request->user, $permission)) {
            return $this->handleNoAuthority($request);
        }

        return $next($request);
    }

    /**
     * {@inheritdoc}
     * @return bool
     */
    public function requestHasPermission(Request $request, UserContract $user, $permission): bool
    {
        if (!$user->can($permission)) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     * @return Response
     */
    public function handleNotLoggedIn(Request $request): Response
    {
        //return Response::create(['message' => '用户未登录', 'code' => 101], 'json', 400);
        //增加use lion9966\exception\ResponseCode后采用以下方式
        //增加语言lang目录，
        //且需要手动加载语言包
        //$this->lang('User is not login');
        return Response::create(['message' => $this->lang('User is not login'), 'code' => ResponseCode::LOGIN_ERROR], 'json', 400);
    }

    /**
     * {@inheritdoc}
     * @return Response
     */
    public function handleNoAuthority(Request $request): Response
    {
        //return Response::create(['message' => '没有权限', 'code' => 105], 'json', 400);
        //增加语言lang目录，
        //且需要手动加载语言包
        //$this->lang($lang,'Do not have permission');
        return Response::create(['message' => $this->lang('Do not have permission'), 'code' => ResponseCode::AUTH_ERROR], 'json', 400);
    }

    /**
     * 语言载入
     * @param $name
     * @return string
     */
    protected function lang($name): string
    {
        if (!Lang::has($name)) {
            $getLangSet = Lang::getLangSet();
            Lang::load([app_path() . DIRECTORY_SEPARATOR . 'lion9966' . DIRECTORY_SEPARATOR . 'utils' . DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR . $getLangSet . DIRECTORY_SEPARATOR . 'permission.php']);
        }
        return Lang::get($name);
    }

}
