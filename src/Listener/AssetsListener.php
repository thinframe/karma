<?php

namespace ThinFrame\Karma\Listener;

use Symfony\Component\Finder\Finder;
use ThinFrame\Applications\DependencyInjection\ApplicationAwareTrait;
use ThinFrame\Events\Constant\Priority;
use ThinFrame\Events\ListenerInterface;
use ThinFrame\Karma\Events;
use ThinFrame\Karma\Manager\AssetsManager;

/**
 * Class AssetsListener
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
     * Get event mappings ["event"=>["method"=>"methodName","priority"=>1]]
     *
     * @return array
     */
    public function getEventMappings()
    {
        return [
            Events::ASSETS_MAP => ['method' => 'onAssetsMap', 'priority' => Priority::HIGH]
        ];
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
