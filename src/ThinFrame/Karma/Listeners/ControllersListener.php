<?php

namespace ThinFrame\Karma\Listeners;

use ThinFrame\Annotations\Processor;
use ThinFrame\Applications\AbstractApplication;
use ThinFrame\Applications\DependencyInjection\ApplicationAwareInterface;
use ThinFrame\Events\ListenerInterface;
use ThinFrame\Karma\Helpers\FileLoader;

/**
 * Class ControllersListener
 *
 * @package ThinFrame\Karma\Listeners
 * @since   0.2
 */
class ControllersListener implements ListenerInterface, ApplicationAwareInterface
{
    /**
     * @var AbstractApplication
     */
    private $application;

    /**
     * @var Processor
     */
    private $processor;

    /**
     * @param Processor $processor
     */
    public function setProcessor(Processor $processor)
    {
        $this->processor = $processor;
    }

    /**
     * Attach application to current instance
     *
     * @param AbstractApplication $application
     *
     * @return mixed
     */
    public function setApplication(AbstractApplication $application)
    {
        $this->application = $application;
    }


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
