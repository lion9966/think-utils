<?php

namespace lion9966\utils\exception;

use ResponseCode;

trait ResponseJson
{
    /**
     * 成功返回json
     * @param null $message 提示信息
     * @param null $data 返回数据
     * @param int $code 返回代码
     * @param array $header 发送header信息
     * @return Json
     */
    protected function success($message = null, $data = null, $code = ResponseCode::SUCCESS, $header = []): Json
    {
        return json([
            'message' => $message,
            'data'    => $data,
            'code'    => $code,
        ], 200, $header);
    }

    /**
     * 错误返回Json
     * @param null $message 提示信息
     * @param null $data 返回数据
     * @param int $code 错误代码
     * @param array $header 发送header信息
     * @return Json
     */
    protected function error($message = null, $data = null, $code = ResponseCode::ERROR, $header = []): Json
    {
        return json([
            'message' => $message,
            'data'    => $data,
            'code'    => $code,
        ], 200, $header);
    }
}
