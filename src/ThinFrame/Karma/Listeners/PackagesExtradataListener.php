<?php
namespace ThinFrame\Karma\Listeners;

use PhpCollection\Map;
use ThinFrame\Applications\AbstractApplication;
use ThinFrame\Events\Constants\Priority;
use ThinFrame\Events\Dispatcher;
use ThinFrame\Events\DispatcherAwareInterface;
use ThinFrame\Events\ListenerInterface;
use ThinFrame\Events\SimpleEvent;
use ThinFrame\Karma\Helpers\FileLoader;

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
            'karma.power_up'             => [
                'method'   => 'onPowerUp',
                'priority' => Priority::CRITICAL
            ],
            'karma.application.metadata' => [
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
                    'karma.application.metadata',
                    ['application' => $appName, 'metadata' => $metadata]
                )
            );
        }

    }

    /**
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