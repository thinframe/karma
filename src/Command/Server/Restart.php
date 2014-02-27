<?php

namespace ThinFrame\Karma\Command\Server;

use ThinFrame\CommandLine\Commands\AbstractCommand;
use ThinFrame\CommandLine\IO\InputDriverInterface;
use ThinFrame\CommandLine\IO\OutputDriverInterface;
use ThinFrame\Karma\Manager\ServerManager;
use ThinFrame\Pcntl\Helpers\Exec;

/**
 * Class Restart
 *
 * @package ThinFrame\Karma\Command\Server
 * @since   0.2
 */
class Restart extends AbstractCommand
{
    /**
     * @var ServerManager
     */
    private $serverManager;

    /**
     * Constructor
     *
     * @param ServerManager $serverManager
     */
    public function __construct(ServerManager $serverManager)
    {
        $this->serverManager = $serverManager;
    }

    /**
     * Get command argument
     *
     * @return string
     */
    public function getArgument()
    {
        return 'restart';
    }

    /**
     * Get command descriptions
     *
     * @return array
     */
    public function getDescriptions()
    {
        return [
            'server restart' => 'Restart the HTTP server'
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
        if (!$this->serverManager->isRunning()) {
            $outputDriver->writeLine('[error]The HTTP server isn\'t running[/error]');

            return false;
        }
        $command = $this->serverManager->getStartCommand();
        $outputDriver->writeLine('[info]Attempting to stop the current server instance[/info]');
        $this->serverManager->stop();
        sleep(1.5);
        if ($this->serverManager->isRunning()) {
            $outputDriver->writeLine('[error]Failed to stop the current server instance[/error]');

            return false;
        }
        Exec::viaPipe($command . ' >/dev/null 2>&1 &', KARMA_ROOT);
        sleep(1.5);
        if ($this->serverManager->isRunning()) {
            $outputDriver->writeLine('[success]Server restarted[/success]');

            return true;
        } else {
            $outputDriver->writeLine('[error]The server cannot be restarted[/error]');

            return false;
        }

    }
}