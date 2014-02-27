<?php

namespace ThinFrame\Karma\Command\Debug;

use ThinFrame\CommandLine\Commands\AbstractCommand;
use ThinFrame\CommandLine\IO\InputDriverInterface;
use ThinFrame\CommandLine\IO\OutputDriverInterface;

/**
 * Class Main
 *
 * @package ThinFrame\Karma\Command\Debug
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
        return 'debug';
    }

    /**
     * Get command descriptions
     *
     * @return array
     */
    public function getDescriptions()
    {
        return [];
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
