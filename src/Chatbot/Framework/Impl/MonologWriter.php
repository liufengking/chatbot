<?php


namespace Commune\Chatbot\Framework\Impl;


use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;
use Commune\Chatbot\Contracts\ExceptionReporter;

class MonologWriter implements LoggerInterface
{
    use LoggerTrait;

    /**
     * @var Logger
     */
    protected $monolog;

    /**
     * @var ExceptionReporter
     */
    protected $reporter;

    /**
     * MonologWriter constructor.
     * @param Logger $monolog
     * @param ExceptionReporter $reporter
     */
    public function __construct(Logger $monolog, ExceptionReporter $reporter)
    {
        $this->monolog = $monolog;
        $this->reporter = $reporter;
    }

    public function log($level, $message, array $context = array())
    {
        // 尝试报告异常.
        if ($message instanceof \Throwable) {
            $this->reporter->report($level, $message, $context);
            $message = get_class($message) . ':' . $message->getMessage();
        }
        $this->monolog->log($level, $message, $context);
    }


}