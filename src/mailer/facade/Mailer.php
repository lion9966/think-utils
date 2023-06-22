<?php

namespace lion9966\utils\mailer\facade;

use think\Facade;

/**
 * Class mailer
 *
 * @package lion9966\utils\mailer\facade
 * @mixin \lion9966\utils\mailer\Mailer
 */
class Mailer extends Facade
{
    protected static function getFacadeClass()
    {
        return 'lion9966\utils\mailer\Mailer';
    }
}
