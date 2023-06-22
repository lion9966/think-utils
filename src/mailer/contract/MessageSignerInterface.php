<?php

declare(strict_types=1);

namespace lion9966\utils\mailer\contract;

use Symfony\Component\Mime\Message;

interface MessageSignerInterface
{
    public function sign(Message $message, array $options = []): Message;
}
