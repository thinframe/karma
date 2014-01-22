<?php

namespace ThinFrame\Karma\Commands\Server;

use ThinFrame\CommandLine\ArgumentsContainer;
use ThinFrame\CommandLine\Commands\AbstractCommand;
use ThinFrame\CommandLine\DependencyInjection\OutputDriverAwareTrait;
use ThinFrame\CommandLine\IO\OutputDriverInterface;
use ThinFrame\Events\Dispatcher;
use ThinFrame\Events\DispatcherAwareInterface;
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
class Run extends AbstractCommand
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
            'server run'          => 'Start the HTTP server',
            'server run --daemon' => 'Start the HTTP server as a daemon',
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
                Exec::viaPipe('bin/thinframe server run > /dev/null 2>&1 &', KARMA_ROOT);
                sleep(2);
            }
            if (ServerHelper::isRunning()) {
                $this->outputDriver->send(
                    '[format foreground="green" background="black" effects="bold"]' .
                    ' Server is listening at {host}:{port} [/format]' . PHP_EOL,
                    [
                        'host' => $this->server->getHost(),
                        'port' => $this->server->getPort()
                    ]
                );
            } else {
                $this->outputDriver->send(
                    '[format foreground="red" background="black" effects="bold"] Failed to start server [/format]'
                    . PHP_EOL
                );
                exit(1);
            }

            return;
        }
        $this->dispatcher->trigger(new SimpleEvent('thinframe.server.pre_start'));
        $this->outputDriver->send(
            '[format foreground="red" background="black" effects="bold"]' .
            ' Server will start listening at {host}:{port} [/format]' . PHP_EOL,
            [
                'host' => $this->server->getHost(),
                'port' => $this->server->getPort()
            ]
        );
        ServerHelper::savePID();
        $this->server->start();
    }
}