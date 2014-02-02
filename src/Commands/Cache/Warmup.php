<?php

/**
 * src/Commands/Cache/Warmup.php
 *
 * @author    Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Karma\Commands\Cache;

use ThinFrame\CommandLine\ArgumentsContainer;
use ThinFrame\CommandLine\Commands\AbstractCommand;
use ThinFrame\CommandLine\DependencyInjection\OutputDriverAwareTrait;
use ThinFrame\Events\DispatcherAwareTrait;
use ThinFrame\Events\SimpleEvent;

/**
 * Class Warmup
 *
 * @package ThinFrame\Karma\Commands\Cache
 * @since   0.2
 */
class Warmup extends AbstractCommand
{
    use DispatcherAwareTrait;
    use OutputDriverAwareTrait;

    /**
     * Get the argument the will trigger this command
     *
     * @return string
     */
    public function getArgument()
    {
        return 'warmup';
    }

    /**
     * Get the descriptions for this command
     *
     * @return string[]
     */
    public function getDescriptions()
    {
        return ['cache warmup' => 'Warmup cache'];
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
        $this->outputDriver->send('[info]Warming up cache ...[/info]');
        $this->dispatcher->trigger(new SimpleEvent('karma.cache.warmup'));
        $this->outputDriver->send('[success]Done[/success]');
        exit(0);
    }
}
