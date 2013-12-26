<?php
namespace ThinFrame\Karma\Commands;

use ThinFrame\CommandLine\ArgumentsContainer;
use ThinFrame\CommandLine\Commands\AbstractCommand;
use ThinFrame\CommandLine\Commands\Commander;
use ThinFrame\CommandLine\Commands\Iterators\DescriptionsIterator;
use ThinFrame\CommandLine\IO\OutputDriverInterface;

/**
 * Class Help
 *
 * @package ThinFrame\Karma\Commands
 * @since   0.2
 */
class Help extends AbstractCommand
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
    public function __construct(Commander $commander, OutputDriverInterface $outputDriver)
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
        return ['help' => 'Show this list'];
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

        $maxSize = max(array_map('strlen', array_keys($descriptionsIterator->getDescriptions())));

        $this->outputDriver->send(PHP_EOL);

        foreach ($descriptionsIterator->getDescriptions() as $key => $value) {
            $this->outputDriver->send(
                '  [format foreground="green" effects="bold"]{command}[/format]',
                ['command' => str_pad($key, $maxSize + 4, " ", STR_PAD_RIGHT)]
            );
            $this->outputDriver->send(
                '- [format effects="bold"]{description}[/format]' . PHP_EOL,
                ['description' => $value]
            );
        }

        $this->outputDriver->send(PHP_EOL);
    }
}
