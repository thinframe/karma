<?php

namespace ThinFrame\Karma\Commands;

use ThinFrame\CommandLine\ArgumentsContainer;
use ThinFrame\CommandLine\Commands\AbstractCommand;
use ThinFrame\CommandLine\IO\OutputDriverInterface;

/**
 * Class ServerCommand
 *
 * @package ThinFrame\Karma\Commands
 * @since   0.1
 */
class ServerCommand extends AbstractCommand
{
    /**
     * @var OutputDriverInterface
     */
    private $outputDriver;

    /**
     * __construct
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
        return 'server';
    }

    /**
     * Get the descriptions for this command
     *
     * @return string[]
     */
    public function getDescriptions()
    {
        return [
            'server' => 'HTTP server commands'
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
        $this->outputDriver->send(
            '[format foreground="blue" effects="bold"]Please execute one of the following commands:[/format] '
        );
        $this->outputDriver->send(
            '([format effects="bold"]start[/format])' . PHP_EOL
        );
    }
}
