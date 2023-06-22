<?php

namespace lion9966\utils\exception;

use lion9966\utils\exception\handle\ExceptionHandle;
use think\Service;

class ExceptionService extends Service
{

    public function register()
    {
        $this->app->bind('think\exception\Handle', ExceptionHandle::class);
    }
}
