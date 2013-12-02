<?php

/**
 * /src/ThinFrame/Karma/AnnotationsHandlers/RouterHandler.php
 *
 * @copyright 2013 Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Karma\AnnotationsHandlers;

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use ThinFrame\Annotations\AnnotationsHandlerInterface;

/**
 * Class RouterHandler
 *
 * @package ThinFrame\Karma\AnnotationsHandler
 * @since   0.1
 */
class RouterHandler implements AnnotationsHandlerInterface
{
    /**
     * @var RouteCollection
     */
    private $routesCollection;

    /**
     * Constructor
     *
     * @param RouteCollection $collection
     */
    public function __construct(RouteCollection $collection)
    {
        $this->routesCollection = $collection;
    }

    /**
     * Handle class annotations
     *
     * @param mixed            $targetObj
     * @param \ReflectionClass $reflection
     * @param array            $annotations
     *
     * @return mixed
     */
    public function handleClassAnnotations(\ReflectionClass $reflection, array $annotations, $targetObj = null)
    {
        // noop
    }

    /**
     * Handle method annotations
     *
     * @param mixed             $targetObj
     * @param \ReflectionMethod $reflection
     * @param array             $annotations
     *
     * @return mixed
     */
    public function handleMethodAnnotations(\ReflectionMethod $reflection, array $annotations, $targetObj = null)
    {
        if (isset($annotations['Route'])) {
            foreach ($annotations['Route'] as $route) {
                if ($route instanceof \stdClass && isset($route->path)) {
                    $symfonyRoute = new Route(
                        $route->path,
                        isset($route->defaults) ? $route->defaults : [],
                        isset($route->requirements) ? $route->requirements : [],
                        isset($route->options) ? $route->options : [],
                        isset($route->host) ? $route->host : '',
                        isset($route->schemes) ? $route->schemes : [],
                        isset($route->methods) ? $route->methods : []
                    );
                    $symfonyRoute->setOption('karmaController', $reflection->getDeclaringClass()->getName());
                    $symfonyRoute->setOption('karmaMethod', $reflection->getName());
                    $this->routesCollection->add(
                        isset($route->name) ? $route->name : $reflection->getDeclaringClass()->getName(
                            ) . ':' . $reflection->getName(),
                        $symfonyRoute
                    );
                }
            }
        }
    }

    /**
     * Handle property annotations
     *
     * @param mixed               $targetObj
     * @param \ReflectionProperty $reflection
     * @param array               $annotations
     *
     * @return mixed
     */
    public function handlePropertyAnnotations(\ReflectionProperty $reflection, array $annotations, $targetObj = null)
    {
        // noop
    }
}
