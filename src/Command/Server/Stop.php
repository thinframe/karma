<?php

namespace ThinFrame\Karma\Command\Server;

use ThinFrame\CommandLine\Commands\AbstractCommand;
use ThinFrame\CommandLine\IO\InputDriverInterface;
use ThinFrame\CommandLine\IO\OutputDriverInterface;
use ThinFrame\Karma\Manager\ServerManager;

/**
 * Class Stop
 *
 * @package ThinFrame\Karma\Commands\Server
 * @since   0.2
 */
class Stop extends AbstractCommand
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
        return 'stop';
    }

    /**
     * Get command descriptions
     *
     * @return array
     */
    public function getDescriptions()
    {
        return [
            'server stop' => 'Stop the HTTP server'
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
            $outputDriver->writeLine('[info]The server is not running[/info]');

            return true;
        }
        if ($this->serverManager->stop()) {
            $outputDriver->writeLine('[info]Attempting to stop the server ... [/info]');
            sleep(1.5);
            if ($this->serverManager->isRunning()) {
                $outputDriver->writeLine('[error]Failed to stop the server[/error]');

                return false;
            } else {
                $outputDriver->writeLine('[success]Server stopped[/success]');

                return true;
            }
        } else {
            $outputDriver->writeLine('[error]Cannot stop the HTTP server (Out of control).[/error]');

            return false;
        }
    }
}
