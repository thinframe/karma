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
use ThinFrame\Events\SimpleEvent;
use ThinFrame\Http\Utils\MimeTypeGuesser;

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
        return ['thinframe.http.inbound_request' => ['method' => 'onRequest', 'priority' => Priority::MAX]];
    }

    /**
     * Handle request
     *
     * @param SimpleEvent $event
     */
    public function onRequest(SimpleEvent $event)
    {
        $request  = $event->getPayload()->get('request')->get();
        $response = $event->getPayload()->get('response')->get();
        /* @var $request \ThinFrame\Server\HttpRequest */
        /* @var $response \ThinFrame\Server\HttpResponse */
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
