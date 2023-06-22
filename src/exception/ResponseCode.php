<?php

namespace lion9966\utils\exception;

/**
 * 响应代码
 * 1开头: 登陆退出错误
 * 2开头: 格式等错误
 * 3开头: 记录存储修改等错误
 * 4开头:
 * 5开头:
 */
class ResponseCode
{
    const SUCCESS = 200; //通用成功
    const ERROR   = 400; //通用错误
    const INVALID = 500; //服务错误

    const LOGIN_ERROR        = 101; //登陆错误
    const LOGOUT_ERROR       = 102; //退出错误
    const ACCESS_TOKEN_ERROR = 103; //访问令牌错误
    const AUTH_ERROR         = 105; //授权错误

    const EMPTY_PARAM   = 204; //空参数
    const PARAM_INVALID = 203; //参数无效

    const RECORD_NOT_FOUND = 301; //记录未找到
    const DELETE_FAILED    = 302; //记录删除失败
    const CREATE_FAILED    = 303; //添加记录失败
    const UPDATE_FAILED    = 304; //更新记录失败
}
