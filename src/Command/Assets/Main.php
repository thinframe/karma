<?php

namespace ThinFrame\Karma\Command\Assets;

use ThinFrame\CommandLine\Command\AbstractCommand;
use ThinFrame\CommandLine\IO\InputDriverInterface;
use ThinFrame\CommandLine\IO\OutputDriverInterface;

/**
 * Class Main
 * @package ThinFrame\Karma\Command\Assets
 * @since   0.3
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
        return 'assets';
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
