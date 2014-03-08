<?php

namespace ThinFrame\Karma\Listener;

use Stringy\StaticStringy;
use ThinFrame\Annotations\Processor;
use ThinFrame\Applications\DependencyInjection\ApplicationAwareTrait;
use ThinFrame\Events\ListenerInterface;
use ThinFrame\Karma\Controller\Router;
use ThinFrame\Karma\Events;

/**
 * Class RoutesLoaderListener
 *
 * @package ThinFrame\Karma\Listener
 * @since   0.3
 */
class RoutesLoaderListener implements ListenerInterface
{
    use ApplicationAwareTrait;

    /**
     * @var Router
     */
    private $router;

    /**
     * @var Processor
     */
    private $processor;

    /**
     * Constructor
     *
     * @param Router    $router
     * @param Processor $processor
     */
    public function __construct(Router $router, Processor $processor)
    {
        $this->router    = $router;
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
        foreach ($this->application->getMetadata() as $applicationName => $metadata) {
            if ($metadata->containsKey('controllers')) {

                $controllers = $this->getApplicationControllers($metadata->get('namespace')->get());
                $this->router->registerControllers($metadata->get('namespace')->get(), $controllers);
                foreach ($metadata->get('routes_prefixes')->getOrElse([]) as $prefix) {
                    $this->router->getApplicationRouteCollection(
                        $metadata->get('namespace')->get()
                    )->addPrefix($prefix);
                }
                foreach ($controllers as $controller) {
                    $this->processor->processClass($controller);
                }
            }
        }
        $this->router->make();
    }

    /**
     * Get controllers for application
     *
     * @param string $namespace
     *
     * @return array
     */
    public function getApplicationControllers($namespace)
    {
        return array_filter(
            get_declared_classes(),
            function ($class) use ($namespace) {
                if (StaticStringy::startsWith($class, $namespace) && in_array(
                        'ThinFrame\Karma\Controller\AbstractController',
                        class_parents($class)
                    )
                ) {
                    return true;
                } else {
                    return false;
                }
            }
        );
    }
}
