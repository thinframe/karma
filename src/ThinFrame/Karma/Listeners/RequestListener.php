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
use ThinFrame\Karma\Exceptions\Http\NotFoundException;
use ThinFrame\Server\Events\HttpRequestEvent;

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
        return [HttpRequestEvent::EVENT_ID => ['method' => 'onRequest', 'priority' => Priority::MIN]];
    }

    /**
     * Handle HTTP request
     *
     * @param HttpRequestEvent $event
     *
     * @throws NotFoundException
     */
    public function onRequest(HttpRequestEvent $event)
    {
        throw new NotFoundException();
    }
}
