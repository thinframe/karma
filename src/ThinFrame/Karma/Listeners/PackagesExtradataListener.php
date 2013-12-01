<?php
namespace ThinFrame\Karma\Listeners;

use ThinFrame\Applications\AbstractApplication;
use ThinFrame\Composer\ComposerHelper;
use ThinFrame\Events\Constants\Priority;
use ThinFrame\Events\Dispatcher;
use ThinFrame\Events\DispatcherAwareInterface;
use ThinFrame\Events\ListenerInterface;
use ThinFrame\Events\SimpleEvent;

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

        foreach ($metadata as $key => $value) {
            switch ($key) {
                case 'path_autoload':
                    //do nothing
                    break;
                default:
                    //noop
                    break;
            }
        }
    }

    /**
     * @param Dispatcher $dispatcher
     */
    public function setDispatcher(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }


}