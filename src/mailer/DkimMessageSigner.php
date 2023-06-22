<?php

declare(strict_types=1);

namespace lion9966\utils\mailer;

use Symfony\Component\Mime\Crypto\DkimSigner;
use Symfony\Component\Mime\Message;

/**
 * @codeCoverageIgnore This class is a trivial proxy that requires no testing
 * 这个类是一个简单的代理,不需要测试
 */
class DkimMessageSigner implements MessageSignerInterface
{
    private DkimSigner $dkimSigner;

    public function __construct(DkimSigner $dkimSigner)
    {
        $this->dkimSigner = $dkimSigner;
    }

    public function sign(Message $message, array $options = []): Message
    {
        return $this->dkimSigner->sign($message, $options);
    }
}
