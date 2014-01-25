<?php

namespace ThinFrame\Karma\Commands\Server;

use ThinFrame\CommandLine\ArgumentsContainer;
use ThinFrame\CommandLine\Commands\AbstractCommand;
use ThinFrame\CommandLine\DependencyInjection\OutputDriverAwareTrait;
use ThinFrame\CommandLine\IO\OutputDriverInterface;
use ThinFrame\Karma\Helpers\ServerHelper;
use ThinFrame\Pcntl\Constants\Signal;
use ThinFrame\Pcntl\Helpers\Exec;
use ThinFrame\Pcntl\Process;

/**
 * Class Restart
 *
 * @package ThinFrame\Karma\Commands\Server
 * @since   0.2
 */
class Restart extends AbstractCommand
{
    use OutputDriverAwareTrait;

    /**
     * Get the argument the will trigger this command
     *
     * @return string
     */
    public function getArgument()
    {
        return 'restart';
    }

    /**
     * Get the descriptions for this command
     *
     * @return string[]
     */
    public function getDescriptions()
    {
        return [
            'server restart' => 'Restart the HTTP server'
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
        if (!ServerHelper::isRunning()) {
            $this->outputDriver->send(
                '[error] Server is not running [/error]' . PHP_EOL,
                [],
                true
            );
            exit(1);
        }
        $process = new Process(ServerHelper::getServerPID());
        if ($process->sendSignal(new Signal(Signal::KILL))) {
            sleep(1);
            Exec::viaPipe('bin/thinframe server run --daemon', KARMA_ROOT);
            $this->outputDriver->send(
                '[info] The server will start shortly [/info]' . PHP_EOL
            );
            exit(0);
        } else {
            $this->outputDriver->send(
                '[error] The server is not responding [/error]' . PHP_EOL,
                [],
                true
            );
            exit(1);
        }
    }
}
