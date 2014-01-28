<?php

/**
 * src/Commands/Debug/Applications.php
 *
 * @author    Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Karma\Commands\Debug;

use PhpCollection\Map;
use ThinFrame\Applications\DependencyInjection\ApplicationAwareTrait;
use ThinFrame\CommandLine\ArgumentsContainer;
use ThinFrame\CommandLine\Commands\AbstractCommand;
use ThinFrame\CommandLine\DependencyInjection\OutputDriverAwareTrait;

/**
 * Class Applications
 *
 * @package ThinFrame\Karma\Commands\Debug
 * @since   0.2
 */
class Applications extends AbstractCommand
{
    use ApplicationAwareTrait;
    use OutputDriverAwareTrait;

    /**
     * Get the argument the will trigger this command
     *
     * @return string
     */
    public function getArgument()
    {
        return 'applications';
    }

    /**
     * Get the descriptions for this command
     *
     * @return string[]
     */
    public function getDescriptions()
    {
        return [
            'debug applications' => 'Show all loaded applications'
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
        $metadata = $this->application->getMetadata();

        $maxSize = max(array_map('strlen', array_keys(iterator_to_array($metadata))));

        foreach ($metadata as $appName => $details) {
            /* @var $details Map */
            $this->outputDriver->send(
                "[format effects='bold' foreground='green'] - {name}[/format]"
                ,
                [
                    'name' => str_pad($appName, $maxSize + 4, " ", STR_PAD_RIGHT)
                ]
            );
            $this->outputDriver->send(
                "[format effects='bold' ]{namespace}[/format]\n"
                ,
                [
                    'namespace' => $details->get('application_namespace')->getOrElse('<none>')
                ]
            );
        }
    }
}
