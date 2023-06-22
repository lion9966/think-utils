<?php

namespace lion9966\utils\exception\handle;

use lion9966\utils\exception\ResponseJson;
use think\exception\Handle;
use think\Request;
use think\Response;
use Throwable;

class ExceptionHandle extends Handle
{
    use ResponseJson;

    public function render($request, Throwable $e): Response
    {
        // error json返回
        if ($e instanceof FailException) {
            return $this->error($e->getMessage(), $e->getData(), $e->getCode(), $e->getHeader());
        }

        //success json返回
        if ($e instanceof CorrectException) {
            return $this->success($e->getMessage(), $e->getData(), $e->getCode(), $e->getHeader());
        }
        return parent::render($request, $e);
    }
}
