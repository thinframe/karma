<?php

namespace ThinFrame\Karma\Command\Server;

use ThinFrame\CommandLine\Commands\AbstractCommand;
use ThinFrame\CommandLine\IO\InputDriverInterface;
use ThinFrame\CommandLine\IO\OutputDriverInterface;
use ThinFrame\Karma\Managers\ServerManager;

/**
 * Class Status
 *
 * @package ThinFrame\Karma\Command\Server
 * @since   0.2
 */
class Status extends AbstractCommand
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
        return 'status';
    }

    /**
     * Get command descriptions
     *
     * @return array
     */
    public function getDescriptions()
    {
        return [
            'server status' => 'Check HTTP server status'
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
        if ($this->serverManager->isRunning()) {
            $outputDriver->writeLine('[info]The HTTP server is running[/info]');

            return true;
        } else {
            $outputDriver->writeLine('[info]The HTTP server isn\'t running[/info]');

            return false;
        }
    }
}
