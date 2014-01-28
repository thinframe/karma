<?php

/**
 * vendor/thinframe/karma/src/Commands/Debug/Main.php
 *
 * @author    Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Karma\Commands\Debug;

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
        return 'debug';
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
        exit(0);
    }

}