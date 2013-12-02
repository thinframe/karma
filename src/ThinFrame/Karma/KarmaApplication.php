<?php

/**
 * /src/ThinFrame/Karma/KarmaApplication.php
 *
 * @copyright 2013 Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Karma;

use ThinFrame\Annotations\AnnotationsApplication;
use ThinFrame\Applications\AbstractApplication;
use ThinFrame\Applications\DependencyInjection\ContainerConfigurator;
use ThinFrame\CommandLine\CommandLineApplication;
use ThinFrame\Events\EventsApplication;
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
     * initialize configurator
     *
     * @param ContainerConfigurator $configurator
     *
     * @return mixed
     */
    public function initializeConfigurator(ContainerConfigurator $configurator)
    {
        //noop
    }

    /**
     * Get configuration files
     *
     * @return mixed
     */
    public function getConfigurationFiles()
    {
        return [
            'resources/listeners.yml',
            'resources/commands.yml',
            'resources/parameters.yml',
            'resources/loggers.yml',
            'resources/annotations_handlers.yml',
            'resources/services.yml',
        ];
    }

    /**
     * Get application name
     *
     * @return string
     */
    public function getApplicationName()
    {
        return 'ThinFrameKarma';
    }

    /**
     * Get parent applications
     *
     * @return AbstractApplication[]
     */
    protected function getParentApplications()
    {
        return [
            new EventsApplication(),
            new CommandLineApplication(),
            new AnnotationsApplication(),
            new ServerApplication()
        ];
    }
}
