<?php

/**
 * /src/ThinFrame/Karma/Commands/HelpCommand.php
 *
 * @copyright 2013 Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Karma\Commands;

use ThinFrame\CommandLine\ArgumentsContainer;
use ThinFrame\CommandLine\Commands\AbstractCommand;
use ThinFrame\CommandLine\Commands\Commander;
use ThinFrame\CommandLine\Commands\Iterators\DescriptionsIterator;
use ThinFrame\CommandLine\IO\OutputDriverInterface;

/**
 * Class HelpCommand
 *
 * @package ThinFrame\Karma\Commands
 * @since   0.1
 */
class HelpCommand extends AbstractCommand
{
    /**
     * @var Commander
     */
    private $commander;
    /**
     * @var OutputDriverInterface
     */
    private $outputDriver;

    /**
     * Constructor
     *
     * @param Commander             $commander
     * @param OutputDriverInterface $outputDriver
     */
    function __construct(Commander $commander, OutputDriverInterface $outputDriver)
    {
        $this->commander    = $commander;
        $this->outputDriver = $outputDriver;
    }

    /**
     * Get the argument the will trigger this command
     *
     * @return string
     */
    public function getArgument()
    {
        return 'help';
    }

    /**
     * Get the descriptions for this command
     *
     * @return string[]
     */
    public function getDescriptions()
    {
        return [
            'help' => 'Show all available commands'
        ];
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
        $descriptionsIterator = new DescriptionsIterator();
        $this->commander->iterate($descriptionsIterator);

        $this->outputDriver->send(PHP_EOL);

        foreach ($descriptionsIterator->getDescriptions() as $command => $description) {
            $this->outputDriver->send(
                '[format foreground="green" effects="bold"]{command}[/format]  ',
                ['command' => $command]
            );
            $this->outputDriver->send(
                '[format effects="bold"]{description}[/format]' . PHP_EOL
                ,
                ['description' => $description]
            );
        }

        $this->outputDriver->send(PHP_EOL);
    }
}
