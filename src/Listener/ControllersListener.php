<?php

namespace ThinFrame\Karma\Listener;

use Stringy\StaticStringy;
use Symfony\Component\Finder\Finder;
use ThinFrame\Applications\DependencyInjection\ApplicationAwareTrait;
use ThinFrame\Events\Constants\Priority;
use ThinFrame\Events\ListenerInterface;
use ThinFrame\Karma\Events;

/**
 * Class ControllersListener
 *
 * @package ThinFrame\Karma\Listener
 * @since   0.3
 */
class ControllersListener implements ListenerInterface
{
    use ApplicationAwareTrait;

    /**
     * Get event mappings ["event"=>["method"=>"methodName","priority"=>1]]
     *
     * @return array
     */
    public function getEventMappings()
    {
        return [
            Events::PRE_SERVER_START => [
                'method'   => 'loadControllers',
                'priority' => Priority::MAX
            ]
        ];
    }

    /**
     * Load controllers
     */
    public function loadControllers()
    {
        $controllersFinder = new Finder();
        $controllersFinder->files();
        foreach ($this->application->getMetadata() as $metadata) {
            $controllersLocations = $metadata->get('controllers')->getOrElse([]);
            foreach ($controllersLocations as $location) {
                $controllersFinder->in($metadata->get('path')->get() . DIRECTORY_SEPARATOR . $location);
            }
        }
        $controllersFinder->filter(
            function (\SplFileInfo $file) {
                if (StaticStringy::endsWith($file, 'Controller.php')) {
                    return true;
                }

                return false;
            }
        );

        foreach ($controllersFinder as $file) {
            require_once $file;
        }
    }
}
