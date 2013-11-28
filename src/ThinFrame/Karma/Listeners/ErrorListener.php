<?php
namespace ThinFrame\Karma\Listeners;

use Psr\Log\LoggerInterface;
use ThinFrame\Events\Constants\Priority;
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
     * @var LoggerInterface
     */
    private $logger;

    /**
     * __construct
     */
    function __construct()
    {
        set_exception_handler([$this, 'forwardException']);
        set_error_handler([$this, 'forwardError']);
    }

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
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
                'method'   => 'onException',
                'priority' => Priority::MIN
            ],
            ErrorEvent::EVENT_ID     => [
                'method'   => 'onError',
                'priority' => Priority::MIN
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
        $this->logger->critical(
            'Exception occured: ' . $event->getException()->getMessage(),
            ['exception' => $event->getException()]
        );
        exit($event->getException()->getCode());
    }

    /**
     * Handle error event
     *
     * @param ErrorEvent $event
     */
    public function onError(ErrorEvent $event)
    {
        $this->logger->error($event->getMessage(), iterator_to_array($event->getPayload()));
        exit($event->getNumber());
    }
}
