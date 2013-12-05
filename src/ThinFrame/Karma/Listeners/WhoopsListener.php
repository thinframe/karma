<?php

/**
 * /src/ThinFrame/Karma/Listeners/WhoopsListener.php
 *
 * @copyright 2013 Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Karma\Listeners;

use ThinFrame\Events\ListenerInterface;
use ThinFrame\Http\Constants\StatusCode;
use ThinFrame\Karma\Constants\Environment;
use ThinFrame\Server\Events\UnknownHttpExceptionEvent;
use Whoops\Run;

/**
 * Class WhoopsListener
 *
 * @package ThinFrame\Karma\Listeners
 * @since   0.1
 */
class WhoopsListener implements ListenerInterface
{
    /**
     * @var Run
     */
    private $whoops;
    /**
     * @var Environment
     */
    private $environment;

    /**
     * Constructor
     *
     * @param Run         $whoops
     * @param Environment $environment
     */
    public function __construct(Run $whoops, Environment $environment)
    {
        $this->whoops      = $whoops;
        $this->environment = $environment;
    }

    /**
     * Get event mappings ["event"=>["method"=>"methodName","priority"=>1]]
     *
     * @return array
     */
    public function getEventMappings()
    {
        if ($this->environment->equals(Environment::DEVELOPMENT)) {
            return [
                UnknownHttpExceptionEvent::EVENT_ID => [
                    'method' => 'onUnknownException'
                ]
            ];
        } else {
            return [];
        }
    }

    /**
     * Handle unknown exception
     *
     * @param UnknownHttpExceptionEvent $event
     */
    public function onUnknownException(UnknownHttpExceptionEvent $event)
    {
        $this->whoops->writeToOutput(false);
        $errorPage = $this->whoops->handleException($event->getHttpException());
        $event->getResponse()->addContent($errorPage);
        $event->getResponse()->setStatusCode(new StatusCode(StatusCode::INTERNAL_SERVER_ERROR));
        $event->getResponse()->end();

        $event->stopPropagation();
    }
}
