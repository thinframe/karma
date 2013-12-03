<?php

/**
 * /src/ThinFrame/Karma/Listeners/AssetsResolverListener.php
 *
 * @copyright 2013 Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Karma\Listeners;

use React\EventLoop\LoopInterface;
use React\Stream\Stream;
use ThinFrame\Events\Constants\Priority;
use ThinFrame\Events\ListenerInterface;
use ThinFrame\Http\Utils\MimeTypeGuesser;
use ThinFrame\Server\Events\HttpRequestEvent;

/**
 * Class AssetsResolverListener
 *
 * @package ThinFrame\Karma\Listeners
 * @since   0.1
 */
class AssetsResolverListener implements ListenerInterface
{
    private $assetsLocation = '';
    /**
     * @var LoopInterface
     */
    private $serverLoop;

    /**
     * Constructor
     *
     * @param string        $assetsLocation
     * @param LoopInterface $loop
     */
    public function __construct($assetsLocation, LoopInterface $loop)
    {
        $this->assetsLocation = $assetsLocation;
        $this->serverLoop     = $loop;
    }

    /**
     * Get event mappings ["event"=>["method"=>"methodName","priority"=>1]]
     *
     * @return array
     */
    public function getEventMappings()
    {
        return [HttpRequestEvent::EVENT_ID => ['method' => 'onRequest', 'priority' => Priority::MAX]];
    }

    /**
     * Handle request
     *
     * @param HttpRequestEvent $event
     */
    public function onRequest(HttpRequestEvent $event)
    {
        $request  = $event->getRequest();
        $response = $event->getResponse();

        $path = $this->assetsLocation . DIRECTORY_SEPARATOR . $request->getPath();

        if (file_exists($path) && is_file($path)) {

            $event->stopPropagation();

            $fileStream = new Stream(fopen($path, "r"), $this->serverLoop);

            $response->getHeaders()->set('Content-Type', MimeTypeGuesser::getMimeType($path));
            $response->dispatchHeaders();

            $fileStream->pipe($response->getReactResponse());

            $fileStream->on('end', 'gc_collect_cycles');

            gc_collect_cycles();
        }
    }
}
