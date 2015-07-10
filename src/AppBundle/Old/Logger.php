<?php
namespace AppBundle\Old;

class Logger implements \LoggerInterface
{
    /**
     * @var string
     */
    private $collector;

    /**
     * @param string $collector
     */
    public function __construct($collector = 'messages')
    {
        $this->setCollector($collector);
    }

    /**
     * @param string $collector
     * @return LoggerInterface
     */
    public function setCollector($collector)
    {
        $this->logger = $collector;

        return $this;
    }

    /**
     * @param string $message
     * @return LoggerInterface
     */
    public function error($message)
    {
        return $this->log($message, 'error');
    }

    /**
     * @param string $message
     * @param string $label
     * @return LoggerInterface
     */
    public function log($message, $label = 'default')
    {
        return $this;
    }
}