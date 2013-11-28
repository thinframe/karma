<?php

namespace ThinFrame\Karma\Commands;

use ThinFrame\CommandLine\ArgumentsContainer;
use ThinFrame\CommandLine\Commands\AbstractCommand;
use ThinFrame\CommandLine\IO\OutputDriverInterface;
use ThinFrame\Server\HttpServer;

/**
 * Class ServerRunCommand
 *
 * @package ThinFrame\Karma\Commands
 * @since   0.1
 */
class ServerRunCommand extends AbstractCommand
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
        $this->outputDriver->send(
            '[format foreground="blue" background="white"] HTTP server is listening at [/format]'
        );
        $this->outputDriver->send(
            '[format foreground="black" effects="bold" background="white"]{host}:{port} [/format]' . PHP_EOL,
            ['host' => $this->server->getHost(), 'port' => $this->server->getPort()]
        );
        $this->server->start();
    }
}