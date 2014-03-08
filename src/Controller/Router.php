<?php

namespace ThinFrame\Karma\Controller;

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * Class Router
 *
 * @package ThinFrame\Karma\Controller
 * @since   0.3
 */
class Router
{
    /**
     * @var RouteCollection
     */
    private $routeCollection;

    /**
     * @var array
     */
    private $controllers = [];

    /**
     * @var RouteCollection[]
     */
    private $subCollections = [];

    /**
     * Constructor
     *
     * @param RouteCollection $routeCollection
     */
    public function __construct(RouteCollection $routeCollection)
    {
        $this->routeCollection = $routeCollection;
    }

    /**
     * Register a controller
     *
     * @param string $namespace
     * @param string $controllerClass
     */
    public function registerController($namespace, $controllerClass)
    {
        if (!isset($this->controllers[$namespace])) {
            $this->controllers[$namespace]    = [];
            $this->subCollections[$namespace] = new RouteCollection();
        }
        $this->controllers[$namespace][] = $controllerClass;
    }

    /**
     * Register controllers
     *
     * @param string $namespace
     * @param array  $controllers
     */
    public function registerControllers($namespace, array $controllers)
    {
        foreach ($controllers as $controller) {
            $this->registerController($namespace, $controller);
        }
    }

    /**
     * Add route
     *
     * @param string $name
     * @param Route  $route
     */
    public function addRoute($name, Route $route)
    {
        $controller = $route->getDefaults()['_controller'];
        if (is_null($namespace = $this->getControllerBaseNamespace($controller))) {
            return;
        }
        $this->getApplicationRouteCollection($namespace)->add($name, $route);
    }

    /**
     * Get application route collection
     *
     * @param string $namespace
     *
     * @return null|RouteCollection
     */
    public function getApplicationRouteCollection($namespace)
    {
        return isset($this->subCollections[$namespace]) ? $this->subCollections[$namespace] : null;
    }

    /**
     * Merge route collections
     */
    public function make()
    {
        foreach ($this->subCollections as $routeCollection) {
            $this->routeCollection->addCollection($routeCollection);
        }
    }

    /**
     * Get controller base namespace
     *
     * @param string $controller
     *
     * @return int|null|string
     */
    private function getControllerBaseNamespace($controller)
    {
        foreach ($this->controllers as $namespace => $controllers) {
            if (in_array($controller, $controllers)) {
                return $namespace;
            }
        }

        return null;
    }
}
