<?php
namespace ThinFrame\Karma\Listeners;

use ThinFrame\CommandLine\IO\OutputDriverInterface;
use ThinFrame\Events\ListenerInterface;
use ThinFrame\Karma\Events\ErrorEvent;
use ThinFrame\Karma\Events\ExceptionEvent;

/**
 * Class CliExceptionsListener
 *
 * @package ThinFrame\Karma\Listeners
 * @since   0.1
 */
class CliErrorListener implements ListenerInterface
{
    /**
     * @var OutputDriverInterface
     */
    private $outputDriver;

    /**
     * __construct
     *
     * @param OutputDriverInterface $outputDriver
     */
    function __construct(OutputDriverInterface $outputDriver)
    {
        $this->outputDriver = $outputDriver;
    }

    /**
     * Get event mappings ["event"=>["method"=>"methodName","priority"=>1]]
     *
     * @return array
     */
    public function getEventMappings()
    {
        return [
            ExceptionEvent::EVENT_ID => [
                'method' => 'onException'
            ],
            ErrorEvent::EVENT_ID     => [
                'method' => 'onError'
            ]
        ];
    }

    public function onError(ErrorEvent $event)
    {
        $this->sendErrorLine('');
        $this->sendErrorLine('A PHP error has occurred');
        $this->sendErrorLine('');
        $this->sendErrorLine('Message: ' . $event->getMessage());
        $this->sendErrorLine('Number: ' . $event->getNumber());
        $this->sendErrorLine('File: ' . $event->getFile());
        $this->sendErrorLine('Line: ' . $event->getLine());
        $this->sendErrorLine('');
        $this->sendErrorLine('Please check the logs for more details');
        $this->sendErrorLine('');
    }

    /**
     * Send error line
     *
     * @param string $line
     */
    private function sendErrorLine($line)
    {
        $this->outputDriver->send(
            '[format background="red" foreground="white" effects="bold"][center]{line}[/center][/format]',
            ['line' => $line]
        );
    }

    /**
     * Handle ExceptionEvent
     *
     * @param ExceptionEvent $event
     */
    public function onException(ExceptionEvent $event)
    {
        $exception = $event->getException();
        $this->sendErrorLine('');
        $this->sendErrorLine('Fatal error: an exception has occurred');
        $this->sendErrorLine('');
        $this->sendErrorLine('Message: ' . $exception->getMessage());
        $this->sendErrorLine('');
        $this->sendErrorLine('File: ' . $exception->getFile());
        $this->sendErrorLine('Line: ' . $exception->getLine());
        $this->sendErrorLine('');
        $this->sendErrorLine('For more details please check the logs');
        $this->sendErrorLine('');
    }

}