<?php

declare(strict_types=1);

namespace lion9966\utils\mailer;

use Symfony\Component\Mime\Crypto\SMimeSigner;
use Symfony\Component\Mime\Message;

/**
 * @codeCoverageIgnore This class is a trivial proxy that requires no testing
 * 这个类是一个简单的代理,不需要测试
 */
class SMimeMessageSigner implements MessageSignerInterface
{
    private SMimeSigner $signer;

    public function __construct(SMimeSigner $signer)
    {
        $this->signer = $signer;
    }

    public function sign(Message $message, array $options = []): Message
    {
        return $this->signer->sign($message);
    }
}
