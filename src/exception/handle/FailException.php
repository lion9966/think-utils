<?php

namespace lion9966\utils\exception\handle;

use lion9966\utils\exception\ResponseCode;
use Exception;

class FailException extends Exception
{
    /**
     * 错误码
     * @var int|int
     */
    protected $code = ResponseCode::ERROR;

    /**
     * 错误信息
     * @var string|string
     */
    protected $message = 'Parameter error';

    /**
     * 数据
     * @var array
     */
    protected $data = [];

    /**
     * header 参数
     * @var array
     */
    protected $header = [];

    /**
     * 初始化错误参数
     * @param string $message
     * @param int|int $code
     * @param array $data
     * @param array $header
     */
    public function __construct(string $message, int $code = ResponseCode::ERROR, $data = [], array $header = [])
    {
        parent::__construct($message, $code);
        $this->code    = $code;
        $this->message = $message;
        $this->data    = $data;
        $this->header  = $header;
    }

    public function getHeader()
    {
        return $this->header;
    }

    public function getData()
    {
        return $this->data;
    }

}
