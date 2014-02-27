<?php

namespace ThinFrame\Karma\Command\Debug\Applications;

use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use ThinFrame\Applications\DependencyInjection\ApplicationAwareTrait;
use ThinFrame\CommandLine\Commands\AbstractCommand;
use ThinFrame\CommandLine\IO\InputDriverInterface;
use ThinFrame\CommandLine\IO\OutputDriverInterface;

/**
 * Class Metadata
 *
 * @package ThinFrame\Karma\Command\Debug\Applications
 * @since   0.3
 */
class Metadata extends AbstractCommand
{
    use ApplicationAwareTrait;

    /**
     * Get command argument
     *
     * @return string
     */
    public function getArgument()
    {
        return 'metadata';
    }

    /**
     * Get command descriptions
     *
     * @return array
     */
    public function getDescriptions()
    {
        return [
            'debug applications metadata' => 'List metadata for all applications'
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
        foreach ($this->application->getMetadata() as $appName => $metadata) {
            $outputDriver->writeLine("[format foreground='cyan' effects='bold']  * {$appName}[/format]");
            $outputDriver->writeLine("");
            $metadata = iterator_to_array($metadata);
            $maxSize  = max(array_map('strlen', array_keys($metadata)));
            foreach ($metadata as $key => $value) {
                if (is_array($value)) {
                    $value = implode(', ', $value);
                }
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
            $outputDriver->writeLine("");
        }

        return true;
    }

}