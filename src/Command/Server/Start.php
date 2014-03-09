<?php

namespace ThinFrame\Karma\Command\Server;

use ThinFrame\CommandLine\Command\AbstractCommand;
use ThinFrame\CommandLine\IO\InputDriverInterface;
use ThinFrame\CommandLine\IO\OutputDriverInterface;
use ThinFrame\Karma\Manager\ServerManager;
use ThinFrame\Pcntl\Helper\Exec;

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
     * @var string
     */
    private $outputFile;

    /**
     * @var string
     */
    private $errorFile;

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
     * Set server input file when in daemon mode
     *
     * @param string $errorFile
     */
    public function setErrorFile($errorFile)
    {
        $this->errorFile = KARMA_ROOT . $errorFile;
    }

    /**
     * Set server output file when in daemon mode
     *
     * @param string $outputFile
     */
    public function setOutputFile($outputFile)
    {
        $this->outputFile = KARMA_ROOT . $outputFile;
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
            'server start'                  => 'Verbose start HTTP server',
            'server start --daemon'         => 'Start the HTTP server as a daemon',
            'server start --host=127.0.0.1' => 'Start the HTTP server on host 127.0.0.1',
            'server start --port=1337'      => 'Start the HTTP server on port 1337',
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
            //set up output redirects
            $redirects = '1>>' . $this->outputFile . ' 2>>' . $this->errorFile;

            //execute the start command
            Exec::viaPipe('bin/thinframe server start --plain-text ' . $redirects . ' &', KARMA_ROOT);
            $outputDriver->writeLine('[info]Waiting for the server to start...[/info]');
            sleep(1.5);
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
