<?php

namespace ThinFrame\Karma\Listeners;

use Psr\Log\LoggerInterface;
use ThinFrame\Events\ListenerInterface;
use ThinFrame\Events\SimpleEvent;

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
        return ['thinframe.http.inbound_request' => ['method' => 'onRequest']];
    }

    public function onRequest(SimpleEvent $event)
    {
        $request = $event->getPayload()->get('request')->get();
        /* @var $request \ThinFrame\Http\Foundation\RequestInterface */

        $this->logger->info('Received request from ' . $request->getRemoteIp() . ' to ' . $request->getPath());
    }

}