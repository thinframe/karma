<?php

/**
 * /src/ThinFrame/Karma/Listeners/CommanderListener.php
 *
 * @copyright 2013 Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Karma\Listeners;

use ThinFrame\CommandLine\ArgumentsContainer;
use ThinFrame\CommandLine\Commands\Commander;
use ThinFrame\CommandLine\Commands\Iterators\CompletionIterator;
use ThinFrame\CommandLine\Commands\Iterators\ExecuteIterator;
use ThinFrame\Events\ListenerInterface;

/**
 * Class CommanderListener
 *
 * @package ThinFrame\Karma\Listeners
 * @since   0.1
 */
class CommanderListener implements ListenerInterface
{
    /**
     * @var Commander
     */
    private $commander;
    /**
     * @var ArgumentsContainer;
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
            'karma.power_up' => [
                'method' => 'onPowerUp'
            ]
        ];
    }

    /**
     * Handle karma.power_up event
     */
    public function onPowerUp()
    {
        if ($this->argumentsContainer->getArgumentAt(0) == 'compgen') {
            $this->commander->iterate(new CompletionIterator($this->argumentsContainer));
        } else {
            $this->commander->iterate(new ExecuteIterator($this->argumentsContainer));
        }
    }
}