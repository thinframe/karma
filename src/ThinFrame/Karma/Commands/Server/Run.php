<?php

namespace ThinFrame\Karma\Commands\Server;

use ThinFrame\CommandLine\ArgumentsContainer;
use ThinFrame\CommandLine\Commands\AbstractCommand;
use ThinFrame\CommandLine\IO\OutputDriverInterface;
use ThinFrame\Events\Dispatcher;
use ThinFrame\Events\DispatcherAwareInterface;
use ThinFrame\Events\SimpleEvent;
use ThinFrame\Server\Server;

/**
 * Class Run
 *
 * @package ThinFrame\Karma\Commands\Server
 * @since   0.2
 */
class Run extends AbstractCommand implements DispatcherAwareInterface
{
    /**
     * @var OutputDriverInterface;
     */
    private $outputDriver;

    /**
     * @var Server
     */
    private $server;

    /**
     * @var Dispatcher
     */
    private $dispatcher;

    /**
     * Constructor
     *
     * @param Server                $server
     * @param OutputDriverInterface $outputDriver
     */
    public function __construct(Server $server, OutputDriverInterface $outputDriver)
    {
        $this->server       = $server;
        $this->outputDriver = $outputDriver;
    }

    /**
     * @param Dispatcher $dispatcher
     */
    public function setDispatcher(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }


    /**
     * Get the argument the will trigger this command
     *
     * @return string
     */
    public function getArgument()
    {
        return 'run';
    }

    /**
     * Get the descriptions for this command
     *
     * @return string[]
     */
    public function getDescriptions()
    {
        return ['server run' => 'Start the HTTP server'];
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
        $this->dispatcher->trigger(new SimpleEvent('thinframe.server.pre_start'));
        $this->outputDriver->send(
            '[format foreground="red" background="white" effects="bold"]' .
            'Server will start listening at {host}:{port}[/format]' . PHP_EOL,
            [
                'host' => $this->server->getHost(),
                'port' => $this->server->getPort()
            ]
        );
        $this->server->start();
    }

}