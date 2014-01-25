<?php

/**
 * src/Listeners/CommanderListener.php
 *
 * @author    Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Karma\Listeners;

use ThinFrame\CommandLine\ArgumentsContainer;
use ThinFrame\CommandLine\Commands\Commander;
use ThinFrame\CommandLine\Commands\Iterators\CompletionIterator;
use ThinFrame\CommandLine\Commands\Iterators\ExecuteIterator;
use ThinFrame\Events\ListenerInterface;
use ThinFrame\Events\SimpleEvent;
use ThinFrame\Foundation\Exceptions\Exception;
use ThinFrame\Karma\KarmaApplication;

/**
 * Class CommanderListener
 *
 * @package ThinFrame\Karma\Listeners
 * @since   0.2
 */
class CommanderListener implements ListenerInterface
{
    /**
     * @var Commander
     */
    private $commander;

    /**
     * @var ArgumentsContainer
     */
    private $argumentsContainer;

    /**
     * Constructor
     *
     * @param Commander          $commander
     * @param ArgumentsContainer $argumentsContainer
     */
    public function __construct(Commander $commander, ArgumentsContainer $argumentsContainer)
    {
        $this->commander          = $commander;
        $this->argumentsContainer = $argumentsContainer;
    }

    /**
     * Get event mappings ["event"=>["method"=>"methodName","priority"=>1]]
     *
     * @return array
     */
    public function getEventMappings()
    {
        return [
            KarmaApplication::POWER_UP_EVENT_ID => [
                'method' => 'onPowerUp'
            ]
        ];
    }

    /**
     *
     * Handle power up event
     *
     * @param SimpleEvent $event
     *
     * @throws \ThinFrame\Foundation\Exceptions\Exception
     */
    public function onPowerUp(SimpleEvent $event)
    {
        if ($this->argumentsContainer->getArgumentAt(0) == 'compgen') {
            $this->commander->iterate(new CompletionIterator($this->argumentsContainer));
        } else {
            $this->commander->iterate($executor = new ExecuteIterator($this->argumentsContainer));
            if (!$executor->isStopped()) {
                throw new Exception('Cannot find the command you requested');
            }
        }
    }
}
