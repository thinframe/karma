<?php

namespace ThinFrame\Karma\Listeners;

use ThinFrame\Events\Constants\Priority;
use ThinFrame\Events\ListenerInterface;
use ThinFrame\Events\SimpleEvent;
use ThinFrame\Karma\Constants\Environment;
use ThinFrame\Karma\KarmaApplication;

/**
 * Class RoutinesListener
 *
 * @package ThinFrame\Karma\Listeners
 * @since   0.2
 */
class RoutinesListener implements ListenerInterface
{
    /**
     * @var Environment
     */
    private $environment;

    /**
     * @param Environment $environment
     */
    public function setEnvironment(Environment $environment)
    {
        $this->environment = $environment;
    }

    /**
     * Get event mappings ["event"=>["method"=>"methodName","priority"=>1]]
     *
     * @return array
     */
    public function getEventMappings()
    {
        return [
            KarmaApplication::POWER_UP_EVENT_ID => ['method' => 'onPowerUp', 'priority' => Priority::CRITICAL]
        ];
    }

    /**
     * Handle power up event
     *
     * @param SimpleEvent $e
     */
    public function onPowerUp(SimpleEvent $e)
    {
        if (getenv('THINFRAME_ENVIRONMENT')) {
            $this->environment->setValue(getenv('THINFRAME_ENVIRONMENT'));
        }
    }
}
