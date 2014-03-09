<?php

namespace ThinFrame\Karma\Listener;

use ThinFrame\CommandLine\IO\OutputDriverAwareTrait;
use ThinFrame\Events\Constant\Priority;
use ThinFrame\Events\ListenerInterface;
use ThinFrame\Karma\Events;

/**
 * Class OutputBufferListener
 *
 * @package ThinFrame\Karma\Listener
 * @since   0.3
 */
class OutputBufferListener implements ListenerInterface
{
    use OutputDriverAwareTrait;

    /**
     * Get event mappings ["event"=>["method"=>"methodName","priority"=>1]]
     *
     * @return array
     */
    public function getEventMappings()
    {
        return [
            Events::POWER_UP => [
                'method'   => 'onPowerUp',
                'priority' => Priority::CRITICAL
            ]
        ];
    }

    /**
     * Redirect all uncontrolled output to STDERR
     */
    public function onPowerUp()
    {
        ob_implicit_flush(true);
        ob_start([$this, 'handleOutput'], 2);
    }

    /**
     * Handle the output buffer and redirect it to STDERR
     *
     * @param $buffer
     */
    public function handleOutput($buffer)
    {
        $this->outputDriver->write($buffer, false, true);
    }
}
