<?php

namespace ThinFrame\Karma\Command\Cache;

use ThinFrame\CommandLine\Command\AbstractCommand;
use ThinFrame\CommandLine\IO\InputDriverInterface;
use ThinFrame\CommandLine\IO\OutputDriverInterface;
use ThinFrame\Events\DispatcherAwareTrait;
use ThinFrame\Events\SimpleEvent;
use ThinFrame\Karma\Events;

/**
 * Class Clear
 * @package ThinFrame\Karma\Command\Cache
 * @since   0.3
 */
class Clear extends AbstractCommand
{
    use DispatcherAwareTrait;

    /**
     * Get command argument
     *
     * @return string
     */
    public function getArgument()
    {
        return 'clear';
    }

    /**
     * Get command descriptions
     *
     * @return array
     */
    public function getDescriptions()
    {
        return [
            'cache clear' => 'Clear cache',
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
        $outputDriver->writeLine('[info]Clearing cache ...[/info]');
        $this->dispatcher->trigger(new SimpleEvent(Events::CACHE_CLEAR));

        $outputDriver->writeLine('[info]Warming up cache ... [/info]');
        $this->dispatcher->trigger(new SimpleEvent(Events::CACHE_WARMUP));
        $outputDriver->writeLine('[success]Done[/success]');

        return true;
    }
}
