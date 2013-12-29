<?php

namespace ThinFrame\Karma\Commands\Server;

use ThinFrame\CommandLine\ArgumentsContainer;
use ThinFrame\CommandLine\Commands\AbstractCommand;
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
    /**
     * @var OutputDriverInterface;
     */
    private $outputDriver;

    /**
     * Constructor
     *
     * @param OutputDriverInterface $outputDriver
     */
    public function __construct(OutputDriverInterface $outputDriver)
    {
        $this->outputDriver = $outputDriver;
    }

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
                '[format foreground="white" background="red" effects="bold"] Server is not running [/format]' . PHP_EOL
            );
            return;
        }
        $process = new Process(ServerHelper::getServerPID());
        if ($process->sendSignal(new Signal(Signal::KILL))) {
            sleep(1);
            Exec::viaPipe('bin/thinframe server run --daemon', KARMA_ROOT);
            $this->outputDriver->send(
                '[format foreground="green" background="black" effects="bold"] The server will start shortly [/format]' . PHP_EOL
            );

            return;
        } else {
            $this->outputDriver->send(
                '[format foreground="white" background="red" effects="bold"] The server is not responding [/format]' . PHP_EOL
            );
        }
    }
}
