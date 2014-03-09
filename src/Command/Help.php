<?php

namespace ThinFrame\Karma\Command;

use ThinFrame\CommandLine\Command\AbstractCommand;
use ThinFrame\CommandLine\Command\Commander;
use ThinFrame\CommandLine\Command\Processor\DescriptionsGathererProcessor;
use ThinFrame\CommandLine\IO\InputDriverInterface;
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
     * @var \ThinFrame\CommandLine\Command\Commander
     */
    private $commander;

    /**
     * Constructor
     *
     * @param Commander $commander
     */
    public function __construct(Commander $commander)
    {
        $this->commander = $commander;
    }

    /**
     * Get command argument
     *
     * @return string
     */
    public function getArgument()
    {
        return 'help';
    }

    /**
     * Get command descriptions
     *
     * @return array
     */
    public function getDescriptions()
    {
        return [
            'help'                   => 'Show this list',
            '<command> --quiet'      => 'Suppress any output',
            '<command> --plain-text' => 'Remove any text formatters'
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
        $descriptionsGatherer = new DescriptionsGathererProcessor();
        $this->commander->executeProcessor($descriptionsGatherer);

        $maxSize = max(array_map('strlen', array_keys($descriptionsGatherer->getDescriptions())));

        $outputDriver->writeLine('');

        foreach ($descriptionsGatherer->getDescriptions() as $key => $value) {
            $outputDriver->write(
                '  [format foreground="green" effects="bold"]' . str_pad(
                    $key,
                    $maxSize + 4,
                    " ",
                    STR_PAD_RIGHT
                ) . '[/format]'
            );
            $outputDriver->writeLine(
                '- [format effects="bold"]' . $value . '[/format]'
            );
        }

        $outputDriver->writeLine('');

        return true;
    }
}
