<?php

/**
 * src/Listeners/WhoopsListener.php
 *
 * @author    Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Karma\Listeners;

use ThinFrame\Events\ListenerInterface;
use ThinFrame\Karma\Constants\Environment;
use ThinFrame\Server\Events\UnknownHttpExceptionEvent;
use Whoops\Run;

/**
 * Class WhoopsListener
 *
 * @package ThinFrame\Karma\Listeners
 * @since   0.2
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
     * @param Environment $environment
     * @param Run         $whoops
     */
    public function __construct(Run $whoops, Environment $environment)
    {
        $this->environment = $environment;
        $this->whoops      = $whoops;
    }

    /**
     * Get event mappings ["event"=>["method"=>"methodName","priority"=>1]]
     *
     * @return array
     */
    public function getEventMappings()
    {
        return [
            UnknownHttpExceptionEvent::EVENT_ID => [
                'method' => 'onException'
            ]
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
    }
}
