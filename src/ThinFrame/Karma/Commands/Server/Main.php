<?php

namespace ThinFrame\Karma\Commands\Server;


use string;
use ThinFrame\CommandLine\ArgumentsContainer;
use ThinFrame\CommandLine\Commands\AbstractCommand;

/**
 * Class Main
 *
 * @package ThinFrame\Karma\Commands\Server
 * @since   0.2
 */
class Main extends AbstractCommand
{
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
        return [];
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

    }

}