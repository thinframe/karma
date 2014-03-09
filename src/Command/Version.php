<?php

namespace ThinFrame\Karma\Command;

use ThinFrame\Applications\DependencyInjection\ApplicationAwareTrait;
use ThinFrame\CommandLine\Command\AbstractCommand;
use ThinFrame\CommandLine\IO\InputDriverInterface;
use ThinFrame\CommandLine\IO\OutputDriverInterface;
use ThinFrame\Foundation\Exception\RuntimeException;
use ThinFrame\Pcntl\Helper\Exec;

/**
 * Class Version
 *
 * @package ThinFrame\Karma\Command
 * @since   0.3
 */
class Version extends AbstractCommand
{
    use ApplicationAwareTrait;

    /**
     * Get command argument
     *
     * @return string
     */
    public function getArgument()
    {
        return 'version';
    }

    /**
     * Get command descriptions
     *
     * @return array
     */
    public function getDescriptions()
    {
        return [
            'version' => 'Show Karma Version'
        ];
    }

    /**
     * Code that will be executed when command is triggered
     *
     * @param InputDriverInterface  $inputDriver
     * @param OutputDriverInterface $outputDriver
     *
     * @return bool
     *
     * @throws RuntimeException
     */
    public function execute(InputDriverInterface $inputDriver, OutputDriverInterface $outputDriver)
    {
        $karmaPath = dirname($this->application->getMetadata()['KarmaApplication']->get('path')->get());
        $result    = Exec::viaPipe('git describe --tags', $karmaPath);
        if ($result['exitStatus'] != 0) {
            throw new RuntimeException('Cannot compute Karma version');
        }
        $outputDriver->writeLine('[info] Version: ' . trim($result['stdOut']) . '[/info]');

        return true;
    }
}
