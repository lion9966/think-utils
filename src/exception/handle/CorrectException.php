<?php

namespace lion9966\utils\exception\handle;

use Exception;
use lion9966\utils\exception\ResponseCode;

class CorrectException extends Exception
{
    //错误码-成功
    protected $code = ResponseCode::SUCCESS;
    //错误信息
    protected $message = 'Parameter error';
    //数据
    protected $data = [];
    //header参数
    protected $header = [];

    /**
     * 初始化错误参数
     * @param string $message
     * @param array $data
     * @param int $code
     * @param array|null $header
     */
    public function __construct(string $message, $data = [], int $code = ResponseCode::SUCCESS, array $header = null)
    {
        parent::__construct($message, $code);
        $this->code    = $code;
        $this->message = $message;
        $this->data    = $data;
        $this->header  = $header;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getHeader()
    {
        return $this->header;
    }
}
