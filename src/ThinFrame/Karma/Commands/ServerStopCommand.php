<?php

/**
 * /src/ThinFrame/Karma/Commands/ServerStopCommand.php
 *
 * @copyright 2013 Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Karma\Commands;

use ThinFrame\CommandLine\ArgumentsContainer;
use ThinFrame\CommandLine\Commands\AbstractCommand;
use ThinFrame\CommandLine\IO\OutputDriverInterface;
use ThinFrame\Pcntl\Constants\Signal;
use ThinFrame\Pcntl\Process;

/**
 * Class ServerStopCommand
 *
 * @package ThinFrame\Karma\Commands
 * @since   0.1
 */
class ServerStopCommand extends AbstractCommand
{
    /**
     * @var OutputDriverInterface
     */
    private $output;
    /**
     * @var Process
     */
    private $process;

    /**
     * Constructor
     *
     * @param OutputDriverInterface $output
     */
    public function __construct(OutputDriverInterface $output)
    {
        $this->output = $output;
    }

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
        if (!$this->isAlive()) {
            $this->output->send(
                '[format foreground="red" effects="bold"]Server is not running[/format]' . PHP_EOL
            );
        } else {
            $this->process->sendSignal(new Signal(Signal::KILL));
            sleep(1);
            if ($this->process->isAlive()) {
                $this->output->send(
                    '[format foreground="red" effects="bold"]Server still running[/format]' . PHP_EOL
                );
            } else {
                $this->output->send(
                    '[format foreground="green" effects="bold"]Server stopped[/format]' . PHP_EOL
                );
            }
        }
    }

    /**
     * Check if server process is alive
     *
     * @return bool
     */
    public function isAlive()
    {
        if (file_exists(KARMA_ROOT . 'app/pid/server.pid')) {
            $pid           = file_get_contents(KARMA_ROOT . 'app/pid/server.pid');
            $this->process = new Process(intval($pid));
            return $this->process->isAlive();
        }
        return false;
    }
}
