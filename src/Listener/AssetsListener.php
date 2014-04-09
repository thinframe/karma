<?php

namespace ThinFrame\Karma\Listener;

use React\EventLoop\LoopInterface;
use React\Stream\Stream;
use Symfony\Component\Finder\Finder;
use ThinFrame\Applications\DependencyInjection\ApplicationAwareTrait;
use ThinFrame\Events\Constant\Priority;
use ThinFrame\Events\ListenerInterface;
use ThinFrame\Http\Util\MimeTypeGuesser;
use ThinFrame\Karma\Events;
use ThinFrame\Karma\Manager\AssetsManager;
use ThinFrame\Server\Event\HttpRequestEvent;

/**
 * Class AssetsListener
 *
 * @package ThinFrame\Karma\Listener
 * @since   0.3
 */
class AssetsListener implements ListenerInterface
{
    use ApplicationAwareTrait;

    /**
     * @var AssetsManager
     */
    private $assetsManager;

    /**
     * @var string
     */
    private $projectRoot;

    /**
     * @var string
     */
    private $assetsRoot;

    /**
     * @var LoopInterface
     */
    private $serverLoop;

    /**
     * Constructor
     *
     * @param AssetsManager $assetsManager
     * @param string        $projectRoot
     * @param string        $assetsRoot
     */
    public function __construct(AssetsManager $assetsManager, $projectRoot, $assetsRoot)
    {
        $this->assetsManager = $assetsManager;
        $this->projectRoot   = realpath($projectRoot);
        $this->assetsRoot    = str_replace($this->projectRoot, '', realpath($assetsRoot));
    }

    /**
     * Set the server loop
     *
     * @param LoopInterface $loop
     */
    public function setServerLoop(LoopInterface $loop)
    {
        $this->serverLoop = $loop;
    }

    /**
     * Get event mappings ["event"=>["method"=>"methodName","priority"=>1]]
     *
     * @return array
     */
    public function getEventMappings()
    {
        return [
            Events::ASSETS_MAP         => ['method' => 'onAssetsMap', 'priority' => Priority::HIGH],
            Events::PRE_SERVER_START   => ['method' => 'onAssetsMap', 'priority' => Priority::HIGH],
            HttpRequestEvent::EVENT_ID => ['method' => 'onRequest', 'priority' => Priority::HIGH]
        ];
    }

    /**
     * Serve assets
     *
     * @param HttpRequestEvent $event
     */
    public function onRequest(HttpRequestEvent $event)
    {
        $asset = $this->projectRoot . $this->assetsRoot . $event->getRequest()->getPath();
        
        if (file_exists($asset) && is_file($asset)) {
            $event->stopPropagation();

            $fileStream = new Stream(fopen($asset, 'r'), $this->serverLoop);

            $event->getResponse()->getHeaders()->set('Content-Type', MimeTypeGuesser::getMimeType($asset));

            $event->getResponse()->dispatchHeaders();

            $fileStream->pipe($event->getResponse()->getReactResponse());

            $fileStream->on('end', 'gc_collect_cycles');

            gc_collect_cycles();

            return;
        }
    }

    /**
     * Map assets
     */
    public function onAssetsMap()
    {
        foreach ($this->application->getMetadata() as $applicationName => $metadata) {
            /* @var $metadata \PhpCollection\Map */
            if (!$metadata->containsKey('web_assets')) {
                continue;
            }
            $assetsRoot   = $metadata->get('path')->get() . DIRECTORY_SEPARATOR . $metadata->get('web_assets')->get();
            $relativePath = str_replace($this->projectRoot, '', realpath($assetsRoot));
            if ($relativePath == $this->assetsRoot) {
                continue;
            }
            $this->assetsManager->addRoot($applicationName, $relativePath);

            //find assets and map them
            $finder = new Finder();
            $finder->in($this->projectRoot . $relativePath);
            foreach ($finder->files() as $file) {
                $relativeFilePath = str_replace($this->projectRoot . $relativePath, '', $file);
                $this->assetsManager->addAsset($applicationName, $relativeFilePath);
            }
        }
    }
}
