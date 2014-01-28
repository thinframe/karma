<?php

/**
 * src/Commands/Debug/Routes.php
 *
 * @author    Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Karma\Commands\Debug;

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use ThinFrame\CommandLine\ArgumentsContainer;
use ThinFrame\CommandLine\Commands\AbstractCommand;
use ThinFrame\CommandLine\DependencyInjection\OutputDriverAwareTrait;
use ThinFrame\Events\DispatcherAwareTrait;
use ThinFrame\Events\SimpleEvent;

/**
 * Class Routes
 *
 * @package ThinFrame\Karma\Commands\Debug
 * @since   0.2
 */
class Routes extends AbstractCommand
{
    use DispatcherAwareTrait;
    use OutputDriverAwareTrait;

    /**
     * @var RouteCollection
     */
    private $routeCollection;

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
     * Get the argument the will trigger this command
     *
     * @return string
     */
    public function getArgument()
    {
        return "routes";
    }

    /**
     * Get the descriptions for this command
     *
     * @return string[]
     */
    public function getDescriptions()
    {
        return [
            "debug routes" => "Show all routes"
        ];
    }

    /**
     * This method will be called if this command is triggered
     *
     * @param ArgumentsContainer $arguments
     *
     * @return mixed
     */
    public function execute(ArgumentsContainer $arguments)
    {
        $this->dispatcher->trigger(new SimpleEvent('thinframe.routes.pre_load'));
        $routesTable = [];
        foreach ($this->routeCollection->getIterator() as $name => $route) {
            /* @var $route Route */
            if (count($route->getMethods()) == 0) {
                $routesTable[$route->getPath()] = ['action' => $name, 'method' => '*'];
            }
            foreach ($route->getMethods() as $method) {
                $routesTable[$route->getPath()] = ['action' => $name, 'method' => $method];
            }
        }

        $maxSize = max(array_map("strlen", array_keys($routesTable)));

        foreach ($routesTable as $path => $details) {
            $this->outputDriver->send(
                "[format effects='bold'] {method} \t {path} \t {action} [/format]" . PHP_EOL,
                [
                    'method' => $details['method'],
                    'path'   => str_pad($path, $maxSize + 4, " ", STR_PAD_RIGHT),
                    'action' => $details['action']
                ]
            );
        }
    }
}
