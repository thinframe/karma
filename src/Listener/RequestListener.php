<?php

namespace ThinFrame\Karma\Listener;

use ThinFrame\Events\ListenerInterface;
use ThinFrame\Server\Events\HttpRequestEvent;

/**
 * Class RequestListener
 *
 * @package ThinFrame\Karma\Listener
 * @since   0.3
 */
class RequestListener implements ListenerInterface
{
    /**
     * Get event mappings ["event"=>["method"=>"methodName","priority"=>1]]
     *
     * @return array
     */
    public function getEventMappings()
    {
        return [
            HttpRequestEvent::EVENT_ID => [
                'method' => 'onHttpRequest'
            ]
        ];
    }

    /**
     * @param HttpRequestEvent $event
     */
    public function onHttpRequest(HttpRequestEvent $event)
    {
        echo "Received request ... \n";
    }

}