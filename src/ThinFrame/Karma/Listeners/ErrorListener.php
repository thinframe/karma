<?php
namespace ThinFrame\Karma\Listeners;

use ThinFrame\CommandLine\IO\OutputDriverInterface;
use ThinFrame\Events\ListenerInterface;
use ThinFrame\Karma\Exceptions\PHPErrorException;

/**
 * Class ErrorListener
 *
 * @package ThinFrame\Karma\Listeners
 * @since   0.2
 */
class ErrorListener implements ListenerInterface
{
    /**
     * @var OutputDriverInterface
     */
    private $outputDriver;

    /**
     * Constructor
     */
    public function __construct()
    {
        set_error_handler([$this, 'onPHPError']);
        set_exception_handler([$this, 'onException']);
    }

    /**
     * Get event mappings ["event"=>["method"=>"methodName","priority"=>1]]
     *
     * @return array
     */
    public function getEventMappings()
    {
        return [];
    }

    /**
     * @param OutputDriverInterface $outputDriver
     */
    public function setOutputDriver(OutputDriverInterface $outputDriver)
    {
        $this->outputDriver = $outputDriver;
    }

    /**
     * Handle PHP errors
     *
     * @param string $number
     * @param string $message
     * @param string $file
     * @param int    $line
     * @param array  $context
     *
     * @throws \ThinFrame\Karma\Exceptions\PHPErrorException
     */
    public function onPHPError($number, $message, $file, $line, $context = [])
    {
        throw new PHPErrorException($message, $number, $file, $line);
    }

    /**
     * Handle uncaught exceptions
     *
     * @param \Exception $exception
     */
    public function onException(\Exception $exception)
    {
        $this->outputDriver->send(PHP_EOL);
        $this->sendErrorLine('');
        $this->sendErrorLine('Error: an exception has occurred');
        $this->sendErrorLine('');
        $this->sendErrorLine('Message: ' . $exception->getMessage());
        $this->sendErrorLine('');
        $this->sendErrorLine('File: ' . $exception->getFile());
        $this->sendErrorLine('Line: ' . $exception->getLine());
        $this->sendErrorLine('');
        $this->sendErrorLine('For more details please check the logs');
        $this->sendErrorLine('');
        $this->outputDriver->send(PHP_EOL);
    }

    /**
     * Format error line
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
