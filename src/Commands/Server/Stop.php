<?php

/**
 * src/Commands/Server/Stop.php
 *
 * @author    Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Karma\Commands\Server;

use ThinFrame\CommandLine\ArgumentsContainer;
use ThinFrame\CommandLine\Commands\AbstractCommand;
use ThinFrame\CommandLine\DependencyInjection\OutputDriverAwareTrait;
use ThinFrame\Karma\Helpers\ServerHelper;
use ThinFrame\Pcntl\Constants\Signal;
use ThinFrame\Pcntl\Process;

/**
 * Class Stop
 *
 * @package ThinFrame\Karma\Commands\Server
 * @since   0.2
 */
class Stop extends AbstractCommand
{
    use OutputDriverAwareTrait;

    /**
     * Get the argument the will trigger this command
     *
     * @return string
     */
    public function getArgument()
    {
        return 'stop';
    }

    /**
     * Get the descriptions for this command
     *
     * @return string[]
     */
    public function getDescriptions()
    {
        return [
            'server stop' => 'Stop the HTTP server'
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
            $this->outputDriver->send(
                '[success] Server will stop shortly [/success]' . PHP_EOL
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
