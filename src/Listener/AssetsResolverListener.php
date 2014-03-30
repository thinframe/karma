<?php

namespace ThinFrame\Karma\Listener;

use Psy\ExecutionLoop\Loop;
use React\EventLoop\LoopInterface;
use React\Stream\Stream;
use Symfony\Component\Finder\Finder;
use ThinFrame\Applications\DependencyInjection\ApplicationAwareTrait;
use ThinFrame\Events\Constant\Priority;
use ThinFrame\Events\ListenerInterface;
use ThinFrame\Http\Util\MimeTypeGuesser;
use ThinFrame\Karma\Events;
use ThinFrame\Server\Event\HttpRequestEvent;

/**
 * Class AssetsResolverListener
 * @package ThinFrame\Karma\Listener
 * @since   0.3
 */
class AssetsResolverListener implements ListenerInterface
{
    use ApplicationAwareTrait;

    /**
     * @var array
     */
    private $assets;

    /**
     * @var LoopInterface
     */
    private $serverLoop;

    /**
     * Constructor
     *
     * @param LoopInterface $serverLoop
     */
    public function __construct(LoopInterface $serverLoop)
    {
        $this->serverLoop = $serverLoop;
    }

    /**
     * Get event mappings ["event"=>["method"=>"methodName","priority"=>1]]
     *
     * @return array
     */
    public function getEventMappings()
    {
        return [
            Events::PRE_SERVER_START   => ['method' => 'onPreStart'],
            HttpRequestEvent::EVENT_ID => ['method' => 'onRequest', 'priority' => Priority::HIGH]
        ];
    }

    /**
     * Preparing the assets
     */
    public function onPreStart()
    {
        foreach ($this->application->getMetadata() as $appName => $metadata) {
            /* @var $metadata \PhpCollection\Map */
            if ($metadata->containsKey('web_assets')) {
                $assetsPath = realpath(
                    $metadata->get('path')->get() . DIRECTORY_SEPARATOR . $metadata->get('web_assets')->get()
                );
                $finder     = new Finder();
                $finder->in($assetsPath);
                foreach ($finder->files() as $file) {
                    if (!is_array($this->assets[$assetsPath])) {
                        $this->assets[$assetsPath] = [];
                    }
                    $this->assets[$assetsPath][] = str_replace($assetsPath, '', $file);
                }
            }
        }
    }

    /**
     * Resolve assets
     *
     * @param HttpRequestEvent $event
     */
    public function onRequest(HttpRequestEvent $event)
    {
        foreach ($this->assets as $root => $assets) {
            foreach ($assets as $asset) {
                if ($event->getRequest()->getPath() == $asset) {
                    $event->stopPropagation();
                    $fileStream = new Stream(fopen($root . $asset, 'r'), $this->serverLoop);

                    $event->getResponse()->getHeaders()->set(
                        'Content-Type',
                        MimeTypeGuesser::getMimeType($asset)
                    );

                    $event->getResponse()->dispatchHeaders();
                    $fileStream->pipe($event->getResponse()->getReactResponse());

                    $fileStream->on('end', 'gc_collect_cycles');

                    gc_collect_cycles();

                    return;
                }
            }
        }
    }
}
