<?php

namespace ThinFrame\Karma\AnnotationHandler;

use Symfony\Component\Routing\Route;
use ThinFrame\Annotations\AnnotationsHandlerInterface;
use ThinFrame\Foundation\Exceptions\RuntimeException;
use ThinFrame\Karma\Controller\Router;

/**
 * Class RouteHandler
 *
 * @package ThinFrame\Karma\AnnotationHandler
 * @since   0.3
 */
class RouteHandler implements AnnotationsHandlerInterface
{
    /**
     * @var Router
     */
    private $router;

    /**
     * Constructor
     *
     * @param Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * Handle class annotations
     *
     * @param mixed            $targetObj
     * @param \ReflectionClass $reflection
     * @param array            $annotations
     *
     * @return mixed
     *
     */
    public function handleClassAnnotations(\ReflectionClass $reflection, array $annotations, $targetObj = null)
    {
        //noop
    }

    /**
     * Handle method annotations
     *
     * @param mixed             $targetObj
     * @param \ReflectionMethod $reflection
     * @param array             $annotations
     *
     * @return mixed
     *
     * @throws RuntimeException
     */
    public function handleMethodAnnotations(\ReflectionMethod $reflection, array $annotations, $targetObj = null)
    {
        if (isset($annotations['Route'])) {
            foreach ($annotations['Route'] as $routeDetails) {
                if (!is_object($routeDetails)) {
                    throw new RuntimeException('Invalid route format');
                }
                if (!isset($routeDetails->name)) {
                    throw new RuntimeException('Missing route name');
                }
                if (!isset($routeDetails->path)) {
                    throw new RuntimeException('Missing route path');
                }
                $route = new Route($routeDetails->path);
                $route->setDefault('_controller', $reflection->getDeclaringClass()->getName());
                $route->setDefault('_method', $reflection->getName());
                if (isset($routeDetails->defaults) && is_array($routeDetails->defaults)) {
                    $route->addDefaults($routeDetails->defaults);
                }
                if (isset($routeDetails->requirements) && is_array($routeDetails->requirements)) {
                    $route->setRequirements($routeDetails->requirements);
                }
                if (isset($routeDetails->methods) && is_array($routeDetails->methods)) {
                    $route->setMethods($routeDetails->methods);
                }
                $this->router->addRoute($routeDetails->name,$route);
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
        //noop
    }

}