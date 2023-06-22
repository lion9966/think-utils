<?php

declare(strict_types=1);

namespace lion9966\utils\mailer;

use Symfony\Component\Mime\Crypto\SMimeEncrypter;
use Symfony\Component\Mime\Message;

/**
 * @codeCoverageIgnore This class is a trivial proxy that requires no testing
 * 这个类是一个简单的代理,不需要测试
 */
class SMimeMessageEncrypter implements MessageEncrypterInterface
{
    private SMimeEncrypter $encrypter;

    public function __construct(SMimeEncrypter $encrypter)
    {
        $this->encrypter = $encrypter;
    }

    public function encrypt(Message $message): Message
    {
        return $this->encrypter->encrypt($message);
    }
}
