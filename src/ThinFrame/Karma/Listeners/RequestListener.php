<?php

/**
 * /src/ThinFrame/Karma/Listeners/RequestListener.php
 *
 * @copyright 2013 Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Karma\Listeners;

use Psr\Log\LoggerInterface;
use ThinFrame\Events\Constants\Priority;
use ThinFrame\Events\ListenerInterface;
use ThinFrame\Events\SimpleEvent;
use ThinFrame\Http\Constants\StatusCode;

/**
 * Class RequestListener
 *
 * @package ThinFrame\Karma\Listeners
 * @since   0.1
 */
class RequestListener implements ListenerInterface
{

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Constructor
     *
     * @param LoggerInterface $logger
     */
    function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Get event mappings ["event"=>["method"=>"methodName","priority"=>1]]
     *
     * @return array
     */
    public function getEventMappings()
    {
        return ['thinframe.http.inbound_request' => ['method' => 'onRequest', 'priority' => Priority::MIN]];
    }

    /**
     * Handle HTTP request
     *
     * @param SimpleEvent $event
     */
    public function onRequest(SimpleEvent $event)
    {
        $request = $event->getPayload()->get('request')->get();
        /* @var $request \ThinFrame\Http\Foundation\RequestInterface */

        $this->logger->info('Received request from ' . $request->getRemoteIp() . ' to ' . $request->getPath());

        //TODO: dispatch router

        $response = $event->getPayload()->get('response')->get();
        /* @var $response \ThinFrame\Http\Foundation\ResponseInterface */
        $response->setStatusCode(new StatusCode(StatusCode::NOT_FOUND));
        $response->addContent("\0");
        $response->end();
    }
}
