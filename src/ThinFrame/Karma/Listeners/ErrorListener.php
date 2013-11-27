<?php
namespace ThinFrame\Karma\Listeners;

use ThinFrame\Events\Dispatcher;
use ThinFrame\Events\DispatcherAwareInterface;
use ThinFrame\Events\ListenerInterface;
use ThinFrame\Karma\Events\ErrorEvent;
use ThinFrame\Karma\Events\ExceptionEvent;

/**
 * Class ExceptionListener
 *
 * @package ThinFrame\Karma\Listeners
 * @since   0.1
 */
class ErrorListener implements ListenerInterface, DispatcherAwareInterface
{
    /**
     * @var Dispatcher
     */
    private $dispatcher;

    /**
     * __construct
     */
    function __construct()
    {
        set_exception_handler([$this, 'forwardException']);
        set_error_handler([$this, 'forwardError']);
    }

    /**
     * Handle exceptions and forward them to the event system
     *
     * @param \Exception $exception
     */
    public function forwardException(\Exception $exception)
    {
        $this->dispatcher->trigger(new ExceptionEvent($exception));
    }

    /**
     * Handle PHP internal errors
     *
     * @param int    $number
     * @param string $message
     * @param string $file
     * @param int    $line
     */
    public function forwardError($number, $message, $file, $line)
    {
        $this->dispatcher->trigger(new ErrorEvent($number, $message, $file, $line));
    }

    /**
     * @param Dispatcher $dispatcher
     */
    public function setDispatcher(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
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
     * Handle exception event
     *
     * @param ExceptionEvent $event
     */
    public function onException(ExceptionEvent $event)
    {
        //TODO: log exception using monolog
    }
}
