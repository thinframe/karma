<?php

/**
 * /src/ThinFrame/Karma/Listeners/PackagesExtradataListener.php
 *
 * @copyright 2013 Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Karma\Listeners;

use PhpCollection\Map;
use ThinFrame\Applications\AbstractApplication;
use ThinFrame\Events\Constants\Priority;
use ThinFrame\Events\Dispatcher;
use ThinFrame\Events\DispatcherAwareInterface;
use ThinFrame\Events\ListenerInterface;
use ThinFrame\Events\SimpleEvent;
use ThinFrame\Karma\Helpers\FileLoader;
use ThinFrame\Karma\KarmaApplication;

/**
 * Class PackagesExtradataListener
 *
 * @package ThinFrame\Karma\Listeners
 * @since   0.1
 */
class PackagesExtradataListener implements ListenerInterface, DispatcherAwareInterface
{
    /**
     * @var Dispatcher
     */
    private $dispatcher;

    /**
     * Get event mappings ["event"=>["method"=>"methodName","priority"=>1]]
     *
     * @return array
     */
    public function getEventMappings()
    {
        return [
            KarmaApplication::POWER_UP_EVENT_ID             => [
                'method'   => 'onPowerUp',
                'priority' => Priority::CRITICAL
            ],
            KarmaApplication::APPLICATION_METADATA_EVENT_ID => [
                'method' => 'onAppMetadata'
            ]
        ];
    }

    /**
     * Handle thinframe.power_up event
     *
     * @param SimpleEvent $event
     */
    public function onPowerUp(SimpleEvent $event)
    {
        $app = $event->getPayload()->get('application')->get();
        /* @var $app AbstractApplication */
        foreach ($app->getMetadata() as $appName => $metadata) {
            $this->dispatcher->trigger(
                new SimpleEvent(
                    KarmaApplication::APPLICATION_METADATA_EVENT_ID,
                    ['application' => $appName, 'metadata' => $metadata]
                )
            );
        }

    }

    /**
     * Handle application metadata event
     *
     * @param SimpleEvent $event
     */
    public function onAppMetadata(SimpleEvent $event)
    {
        $metadata = $event->getPayload()->get('metadata')->get();
        /* @var $metadata Map */
        foreach ($metadata as $key => $value) {
            switch ($key) {
                case 'path_autoload':
                    FileLoader::doRequire(
                        $metadata->get('application_path')->get() . DIRECTORY_SEPARATOR . $value
                    );
                    break;
                default:
                    //noop
                    break;
            }
        }
    }

    /**
     * Attach Dispatcher
     *
     * @param Dispatcher $dispatcher
     */
    public function setDispatcher(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }
}
