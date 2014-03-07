<?php

namespace ThinFrame\Karma\Listener;

use Stringy\StaticStringy;
use Symfony\Component\Finder\Finder;
use ThinFrame\Annotations\Processor;
use ThinFrame\Applications\DependencyInjection\ApplicationAwareTrait;
use ThinFrame\Events\ListenerInterface;
use ThinFrame\Karma\Events;

/**
 * Class ControllerListener
 *
 * @package ThinFrame\Karma\Listener
 * @since   0.3
 */
class ControllerListener implements ListenerInterface
{
    use ApplicationAwareTrait;

    /**
     * @var Processor
     */
    private $processor;

    /**
     * Construct
     *
     * @param Processor $processor
     */
    public function __construct(Processor $processor)
    {
        $this->processor = $processor;
    }

    /**
     * Get event mappings ["event"=>["method"=>"methodName","priority"=>1]]
     *
     * @return array
     */
    public function getEventMappings()
    {
        return [
            Events::PRE_SERVER_START => [
                'method' => 'loadRoutes'
            ]
        ];
    }

    /**
     * Load routes
     */
    public function loadRoutes()
    {
        $controllersFinder = new Finder();
        $controllersFinder->files();
        foreach ($this->application->getMetadata() as $appName => $metadata) {
            foreach ($metadata->get('controllers')->getOrElse([]) as $controllersPath) {
                $controllersFinder->in($metadata->get('path')->get() . DIRECTORY_SEPARATOR . $controllersPath);
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

        foreach (get_declared_classes() as $class) {
            if (in_array('ThinFrame\Karma\Controller\AbstractController', class_parents($class))) {
                $this->processor->processClass($class);
            }
        }
    }
}
