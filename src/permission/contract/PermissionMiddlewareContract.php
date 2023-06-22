<?php

namespace lion9966\utils\permission\contract;

use think\Request;
use think\Response;

interface PermissionMiddlewareContract
{
    /**
     * 检查是否有权限.
     *
     * @param Request $request
     * @param UserContract $user
     * @param bool
     *
     * @return void
     */
    public function requestHasPermission(Request $request, UserContract $user, $permission);

    /**
     * 用户没有权限.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function handleNoAuthority(Request $request): Response;

    /**
     * 用户未登录.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function handleNotLoggedIn(Request $request): Response;

}
