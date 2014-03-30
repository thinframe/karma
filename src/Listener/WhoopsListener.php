<?php

namespace ThinFrame\Karma\Listener;

use ThinFrame\Applications\DependencyInjection\ApplicationAwareTrait;
use ThinFrame\Events\ListenerInterface;
use ThinFrame\Http\Constant\StatusCode;
use ThinFrame\Karma\Environment;
use ThinFrame\Server\Event\UnknownHttpExceptionEvent;
use Whoops\Run;

/**
 * Class WhoopsListener
 * @package ThinFrame\Karma\Listener
 * @since   0.3
 */
class WhoopsListener implements ListenerInterface
{
    /**
     * @var Environment
     */
    private $environment;
    /**
     * @var Run
     */
    private $whoops;

    /**
     * Constructor
     *
     * @param Run    $whoops
     * @param string $environment
     */
    public function __construct(Run $whoops, $environment)
    {
        $this->whoops      = $whoops;
        $this->environment = new Environment($environment);
    }

    /**
     * Get event mappings ["event"=>["method"=>"methodName","priority"=>1]]
     *
     * @return array
     */
    public function getEventMappings()
    {
        return [
            UnknownHttpExceptionEvent::EVENT_ID => ['method' => 'onException']
        ];
    }

    /**
     * Handle http exceptions
     *
     * @param UnknownHttpExceptionEvent $event
     */
    public function onException(UnknownHttpExceptionEvent $event)
    {
        if (!$this->environment->equals(Environment::DEVELOPMENT)) {
            return;
        }
        $event->stopPropagation();
        $this->whoops->writeToOutput(false);
        $this->whoops->allowQuit(false);
        $content = $this->whoops->handleException($event->getHttpException());
        $event->getResponse()->setContent($content);
        $event->getResponse()->setStatusCode(new StatusCode(StatusCode::INTERNAL_SERVER_ERROR));
    }
}
