<?php

/**
 * src/Listeners/ControllersListener.php
 *
 * @author    Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Karma\Listeners;

use ThinFrame\Annotations\DependencyInjection\ProcessorAwareTrait;
use ThinFrame\Applications\DependencyInjection\ApplicationAwareTrait;
use ThinFrame\Events\ListenerInterface;
use ThinFrame\Karma\Helpers\FileLoader;

/**
 * Class ControllersListener
 *
 * @package ThinFrame\Karma\Listeners
 * @since   0.2
 */
class ControllersListener implements ListenerInterface
{
    use ApplicationAwareTrait;
    use ProcessorAwareTrait;

    /**
     * Get event mappings ["event"=>["method"=>"methodName","priority"=>1]]
     *
     * @return array
     */
    public function getEventMappings()
    {
        return [
            'thinframe.server.pre_start' => [
                'method' => 'onServerPreStart'
            ],
            'thinframe.routes.pre_load'  => [
                'method' => 'onServerPreStart'
            ]
        ];
    }

    /**
     * Handle server pre start event
     */
    public function onServerPreStart()
    {
        // load controllers
        foreach ($this->application->getMetadata() as $metadata) {
            /* @var $metadata \PhpCollection\Map */
            foreach ((array)$metadata->get('controllers')->getOrElse([]) as $controllersRoot) {
                FileLoader::doRequire(
                    $metadata->get('application_path')->get() . DIRECTORY_SEPARATOR . $controllersRoot
                );
            }
        }

        //parse controllers metadata
        foreach (get_declared_classes() as $className) {
            if (in_array('ThinFrame\Karma\ViewController\AbstractController', class_parents($className))) {
                $this->processor->processClass($className);
            }
        }
    }
}
