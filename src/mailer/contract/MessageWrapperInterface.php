<?php

declare(strict_types=1);

namespace lion9966\utils\mailer\contract;

use Symfony\Component\Mime\Email;

interface MessageWrapperInterface
{
    public function getSymfonyMessage(): Email;
}
