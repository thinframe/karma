<?php

/**
 * /src/KarmaApplication.php
 *
 * @author    Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Karma;

use PhpCollection\Map;
use ThinFrame\Annotations\AnnotationsApplication;
use ThinFrame\Applications\AbstractApplication;
use ThinFrame\Applications\DependencyInjection\ContainerConfigurator;
use ThinFrame\CommandLine\CommandLineApplication;
use ThinFrame\Events\EventsApplication;
use ThinFrame\Inotify\InotifyApplication;
use ThinFrame\Karma\DependencyInjection\CompilerPass;
use ThinFrame\Pcntl\PcntlApplication;
use ThinFrame\Server\ServerApplication;

/**
 * Karma Application
 *
 * @version 0.1
 *
 * @package ThinFrame\Karma
 */
class KarmaApplication extends AbstractApplication
{
    /**
     * Get application name
     *
     * @return string
     */
    public function getName()
    {
        return $this->reflector->getShortName();
    }

    /**
     * Get application parents
     *
     * @return AbstractApplication[]
     */
    public function getParents()
    {
        return [
            new EventsApplication(),
            new CommandLineApplication(),
            new AnnotationsApplication(),
            new ServerApplication(),
            new PcntlApplication(),
            new InotifyApplication()
        ];
    }

    /**
     * Set different options for the container configurator
     *
     * @param ContainerConfigurator $configurator
     */
    protected function setConfiguration(ContainerConfigurator $configurator)
    {
        $configurator->addResources(
            [
                'Resources/config/listeners.yml',
                'Resources/config/commands.yml',
                'Resources/config/utils.yml',
                'Resources/config/annotations_handlers.yml',
            ]
        );
        $configurator->addCompilerPass(new CompilerPass());
    }

    /**
     * Set application metadata
     *
     * @param Map $metadata
     *
     */
    protected function setMetadata(Map $metadata)
    {
        //noop
    }
}
