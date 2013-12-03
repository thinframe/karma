<?php

/**
 * /src/ThinFrame/Karma/Commands/ServerRunCommand.php
 *
 * @copyright 2013 Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Karma\Commands;

use ThinFrame\CommandLine\ArgumentsContainer;
use ThinFrame\CommandLine\Commands\AbstractCommand;
use ThinFrame\CommandLine\IO\OutputDriverInterface;
use ThinFrame\Events\Dispatcher;
use ThinFrame\Events\DispatcherAwareInterface;
use ThinFrame\Events\SimpleEvent;
use ThinFrame\Server\HttpServer;

/**
 * Class ServerRunCommand
 *
 * @package ThinFrame\Karma\Commands
 * @since   0.1
 */
class ServerRunCommand extends AbstractCommand implements DispatcherAwareInterface
{
    /**
     * @var HttpServer
     */
    private $server;
    /**
     * @var OutputDriverInterface
     */
    private $outputDriver;
    /**
     * @var Dispatcher
     */
    private $dispatcher;

    /**
     * Constructor
     *
     * @param HttpServer            $server
     * @param OutputDriverInterface $outputDriver
     */
    public function __construct(HttpServer $server, OutputDriverInterface $outputDriver)
    {
        $this->server       = $server;
        $this->outputDriver = $outputDriver;
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
     * Attach dispatcher
     *
     * @param Dispatcher $dispatcher
     */
    public function setDispatcher(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Get the descriptions for this command
     *
     * @return string[]
     */
    public function getDescriptions()
    {
        return [
            'server run' => 'Start the HTTP server'
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
        $this->dispatcher->trigger(new SimpleEvent('karma.server.pre_start'));

        $this->outputDriver->send(
            '[format foreground="blue" background="white"] HTTP server is listening at [/format]'
        );
        $this->outputDriver->send(
            '[format foreground="black" effects="bold" background="white"]{host}:{port} [/format]' . PHP_EOL,
            ['host' => $this->server->getHost(), 'port' => $this->server->getPort()]
        );

        $this->outputDriver->send('Press [format effects=bold]CTRL+C[/format] to stop the server' . PHP_EOL);

        $this->server->start();
    }
}
