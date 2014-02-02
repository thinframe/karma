<?php

/**
 * src/Commands/Server/Run.php
 *
 * @author    Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Karma\Commands\Server;

use ThinFrame\CommandLine\ArgumentsContainer;
use ThinFrame\CommandLine\Commands\AbstractCommand;
use ThinFrame\CommandLine\DependencyInjection\OutputDriverAwareTrait;
use ThinFrame\Events\DispatcherAwareTrait;
use ThinFrame\Events\SimpleEvent;
use ThinFrame\Karma\Helpers\ServerHelper;
use ThinFrame\Pcntl\Helpers\Exec;
use ThinFrame\Server\Server;

/**
 * Class Run
 *
 * @package ThinFrame\Karma\Commands\Server
 * @since   0.2
 */
class Start extends AbstractCommand
{
    use OutputDriverAwareTrait;
    use DispatcherAwareTrait;

    /**
     * @var Server
     */
    private $server;

    /**
     * Constructor
     *
     * @param Server $server
     */
    public function __construct(Server $server)
    {
        $this->server = $server;
    }

    /**
     * Get the argument the will trigger this command
     *
     * @return string
     */
    public function getArgument()
    {
        return 'start';
    }

    /**
     * Get the descriptions for this command
     *
     * @return string[]
     */
    public function getDescriptions()
    {
        return [
            'server start'          => 'Start the HTTP server',
            'server start --daemon' => 'Start the HTTP server as a daemon',
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
        if ($arguments->getOption('daemon')) {
            if (!ServerHelper::isRunning()) {
                Exec::viaPipe('bin/thinframe server start > /dev/null 2>&1 &', KARMA_ROOT);
                sleep(2);
            }
            if (ServerHelper::isRunning()) {
                $this->outputDriver->send(
                    '[success]Server is listening at {host}:{port}[/success]' . PHP_EOL,
                    [
                        'host' => $this->server->getHost(),
                        'port' => $this->server->getPort()
                    ]
                );
                exit(0);
            } else {
                $this->outputDriver->send(
                    '[error]Failed to start server[/error]' . PHP_EOL,
                    [],
                    true
                );
                exit(1);
            }
        }
        $this->dispatcher->trigger(new SimpleEvent('thinframe.server.pre_start'));
        $this->outputDriver->send(
            '[success]Server will start listening at {host}:{port}[/success]' . PHP_EOL,
            [
                'host' => $this->server->getHost(),
                'port' => $this->server->getPort()
            ]
        );
        ServerHelper::savePID();
        $this->server->start();
    }
}
