<?php

namespace ThinFrame\Karma\Command\Debug;

use Psy\Shell;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use ThinFrame\Applications\DependencyInjection\ApplicationAwareTrait;
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
    use ApplicationAwareTrait;
    use ContainerAwareTrait;

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
        return [
            'debug' => 'Open PsySh repl'
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
        Shell::debug(['container' => $this->container, 'application' => $this->application]);

        return true;
    }
}
