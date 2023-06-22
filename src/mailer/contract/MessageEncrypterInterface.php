<?php

declare(strict_types=1);

namespace lion9966\utils\mailer\contract;

use Symfony\Component\Mime\Message as Message;

interface MessageEncrypterInterface
{
    public function encrypt(Message $message): Message;
}
