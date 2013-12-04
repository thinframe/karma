<?php

/**
 * /src/ThinFrame/Karma/Listeners/CliErrorListener.php
 *
 * @copyright 2013 Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Karma\Listeners;

use ThinFrame\CommandLine\IO\OutputDriverInterface;
use ThinFrame\Events\ListenerInterface;
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
     * Construct
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
            ]
        ];
    }

    /**
     * Handle ExceptionEvent
     *
     * @param ExceptionEvent $event
     */
    public function onException(ExceptionEvent $event)
    {
        $exception = $event->getException();
        $this->outputDriver->send(PHP_EOL);
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
        $this->outputDriver->send(PHP_EOL);

        echo $exception->getTraceAsString();
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
}
