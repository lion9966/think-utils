<?php

namespace lion9966\utils\mailer;

use Symfony\Component\Mailer\Mailer as SymfonyMailer;
use Symfony\Component\Mailer\Transport\Dsn;
use Symfony\Component\Mailer\Transport\TransportInterface;
use think\facade\Config;


class Transport
{
    /**
     * @var array|TransportInterface|null Symfony transport instance or its array configuration.
     */
    private $_transport = [];

    private ?SymfonyMailer $symfonyMailer = null;

    /**
     * Creates Symfony mailer instance.
     * @return SymfonyMailer mailer instance.
     */
    private function createSymfonyMailer(): SymfonyMailer
    {
        return new SymfonyMailer($this->getTransport());
    }

    /**
     * @return SymfonyMailer Swift mailer instance
     */
    public function getSymfonyMailer(): SymfonyMailer
    {
        if (!isset($this->symfonyMailer)) {
            $this->symfonyMailer = $this->createSymfonyMailer();
        }
        return $this->symfonyMailer;
    }

    /**
     * @return TransportInterface
     */
    public function getTransport(): TransportInterface
    {
        if (!is_object($this->_transport)) {
            $this->_transport = $this->createTransport($this->_transport);
        }
        return $this->_transport;
    }

    /**
     * @param array|TransportInterface $transport
     * @throws InvalidArgumentException on invalid argument.
     */
    public function setTransport($transport): void
    {
        if (!is_array($transport) && !$transport instanceof TransportInterface) {
            throw new InvalidArgumentException('"' . get_class($this) . '::transport" should be either object or array, "' . gettype($transport) . '" given.');
        }
        if ($transport instanceof TransportInterface) {
            $this->_transport = $transport;
        } elseif (is_array($transport)) {
            $this->_transport = $this->createTransport($transport);
        }

        $this->symfonyMailer = null;
    }


    private function createTransport(array $config = []): TransportInterface
    {
        $config           = array_merge(Config::get('utils.codeMail'), $config);
        $defaultFactories = \Symfony\Component\Mailer\Transport::getDefaultFactories();
        $transportObj     = new \Symfony\Component\Mailer\Transport($defaultFactories);
        if (array_key_exists('dsn', $config) && is_string($config['dsn'])) {
            $transport = $transportObj->fromString($config['dsn']);
        } elseif (array_key_exists('dsn', $config) && $config['dsn'] instanceof Dsn) {
            $transport = $transportObj->fromDsnObject($config['dsn']);
        } elseif (array_key_exists('scheme', $config) && array_key_exists('host', $config)) {
            $dsn       = new Dsn(
                $config['scheme'],
                $config['host'],
                $config['username'] ?? '',
                $config['password'] ?? '',
                $config['port'] ?? null,
                $config['options'] ?? [],
            );
            $transport = $transportObj->fromDsnObject($dsn);
        } else {
            throw new InvalidArgumentException('Transport configuration array must contain either "dsn", or "scheme" and "host" keys.');
        }
        return $transport;
    }

}
