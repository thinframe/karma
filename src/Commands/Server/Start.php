<?php

namespace ThinFrame\Karma\Commands\Server;

use ThinFrame\CommandLine\Commands\AbstractCommand;
use ThinFrame\CommandLine\IO\InputDriverInterface;
use ThinFrame\CommandLine\IO\OutputDriverInterface;
use ThinFrame\Karma\Managers\ServerManager;
use ThinFrame\Pcntl\Helpers\Exec;

/**
 * Class Restart
 *
 * @package ThinFrame\Karma\Commands\Server
 * @since   0.2
 */
class Start extends AbstractCommand
{
    /**
     * @var ServerManager
     */
    private $serverManager;

    /**
     * Construct
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
        return 'start';
    }

    /**
     * Get command descriptions
     *
     * @return array
     */
    public function getDescriptions()
    {
        return [
            'start'                  => 'Verbose start HTTP server',
            'start --daemon'         => 'Start the HTTP server as a daemon',
            'start --host=127.0.0.1' => 'Start the HTTP server on host 127.0.0.1',
            'start --port=1337'      => 'Start the HTTP server on port 1337',
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
        if ($this->serverManager->isRunning() && !$inputDriver->getArgumentsContainer()->isOptionProvided(
                'skip-checks'
            )
        ) {
            $outputDriver->writeLine('[error] The server is already running [/error]', true);

            return false;
        }

        if ($inputDriver->getArgumentsContainer()->isOptionProvided('daemon')) {
            //todo: redirect output to files
            Exec::viaPipe('bin/thinframe server start > /dev/null 2>&1 &', KARMA_ROOT);
            $outputDriver->writeLine('[info]Waiting for the server to start...[/info]');
            sleep(2);
            if ($this->serverManager->isRunning()) {
                $outputDriver->writeLine(
                    '[success]The server is listening at ' . $this->serverManager->getHost(
                    ) . ':' . $this->serverManager->getPort() . '[/success]'
                );

                return true;
            } else {
                $outputDriver->writeLine('[error]Failed to start the server[/error]');

                return false;
            }
        } else {
            $this->serverManager->prepare(
                $inputDriver->getArgumentsContainer()->getOptionValue('host'),
                $inputDriver->getArgumentsContainer()->getOptionValue('port')
            );

            $outputDriver->writeLine(
                "[info]The server will start listening at {$this->serverManager->getHost(
                )}:{$this->serverManager->getPort()}...[/info]"
            );

            $this->serverManager->start();

            return true;
        }
    }
}
