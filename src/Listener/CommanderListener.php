<?php

namespace ThinFrame\Karma\Listener;

use ThinFrame\CommandLine\Commands\Commander;
use ThinFrame\CommandLine\Commands\Processors\CommandFinderProcessor;
use ThinFrame\CommandLine\IO\ArgumentsContainerInterface;
use ThinFrame\CommandLine\IO\InputDriverAwareTrait;
use ThinFrame\CommandLine\IO\OutputDriverAwareTrait;
use ThinFrame\Events\ListenerInterface;
use ThinFrame\Foundation\Exceptions\InvalidArgumentException;
use ThinFrame\Karma\Events;

/**
 * Class CommanderListener
 *
 * @package ThinFrame\Karma\Listeners
 * @since   0.3
 */
class CommanderListener implements ListenerInterface
{
    use InputDriverAwareTrait;
    use OutputDriverAwareTrait;

    /**
     * @var Commander
     */
    private $commander;

    /**
     * @var ArgumentsContainerInterface
     */
    private $argumentsContainer;

    /**
     * Constructor
     *
     * @param Commander                   $commander
     * @param ArgumentsContainerInterface $argumentsContainer
     */
    public function __construct(Commander $commander, ArgumentsContainerInterface $argumentsContainer)
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
        return [Events::POWER_UP => ['method' => 'onPowerUp']];
    }

    /**
     * Execute the provided command
     *
     * @throws \ThinFrame\Foundation\Exceptions\InvalidArgumentException
     */
    public function onPowerUp()
    {
        $processor = new CommandFinderProcessor($this->argumentsContainer);
        $this->commander->executeProcessor($processor);

        if ($command = $processor->getCommand()) {
            if ($command->execute($this->inputDriver, $this->outputDriver)) {
                exit(0);
            }
            exit(1);
        } else {
            throw new InvalidArgumentException('Command cannot be found');
        }
    }
}
