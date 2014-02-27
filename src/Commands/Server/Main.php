<?php

namespace ThinFrame\Karma\Commands\Server;

use ThinFrame\CommandLine\Commands\AbstractCommand;
use ThinFrame\CommandLine\IO\InputDriverInterface;
use ThinFrame\CommandLine\IO\OutputDriverInterface;

/**
 * Class Main
 *
 * @package ThinFrame\Karma\Commands\Server
 * @since   0.2
 */
class Main extends AbstractCommand
{
    /**
     * Get command argument
     *
     * @return string
     */
    public function getArgument()
    {
        return 'server';
    }

    /**
     * Get command descriptions
     *
     * @return array
     */
    public function getDescriptions()
    {
        return [
            'server' => 'Suite of commands to manage the HTTP server'
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
        return true;
    }
}