<?php

namespace ThinFrame\Karma\Command\Debug;

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use ThinFrame\CommandLine\Commands\AbstractCommand;
use ThinFrame\CommandLine\IO\InputDriverInterface;
use ThinFrame\CommandLine\IO\OutputDriverInterface;
use ThinFrame\Events\DispatcherAwareTrait;
use ThinFrame\Events\SimpleEvent;
use ThinFrame\Karma\Events;

/**
 * Class Router
 *
 * @package ThinFrame\Karma\Command\Debug
 * @since   0.3
 */
class Router extends AbstractCommand
{
    use DispatcherAwareTrait;

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
     * Get command argument
     *
     * @return string
     */
    public function getArgument()
    {
        return 'router';
    }

    /**
     * Get command descriptions
     *
     * @return array
     */
    public function getDescriptions()
    {
        return [
            'debug router' => 'Debug router'
        ];
    }

    /**
     * Code that will be executed when command is triggered
     *
     * @param InputDriverInterface  $inputDriver
     * @param OutputDriverInterface $outputDriver
     *
     * @return bool
     */
    public function execute(InputDriverInterface $inputDriver, OutputDriverInterface $outputDriver)
    {
        $this->dispatcher->trigger(new SimpleEvent(Events::PRE_SERVER_START));
        foreach ($this->routeCollection->getIterator() as $name => $route) {
            /* @var $route Route */
            $methods = count($route->getMethods()) > 0 ? implode("|", $route->getMethods()) : "ANY";
            $outputDriver->writeLine(
                "[format foreground='white' effects='bold']{$methods}\t{$name}\t{$route->getPath()}[/format]"
            );
        }

        return true;
    }
}
