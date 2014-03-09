<?php

namespace ThinFrame\Karma\Listener;

use ThinFrame\CommandLine\IO\InputDriverAwareTrait;
use ThinFrame\CommandLine\IO\OutputDriverAwareTrait;
use ThinFrame\Events\Constant\Priority;
use ThinFrame\Events\ListenerInterface;
use ThinFrame\Karma\Events;
use ThinFrame\Karma\IO\Formatter\QuietFormatter;

/**
 * Class QuietOutputListener
 *
 * @package ThinFrame\Karma\Listener
 * @since   0.3
 */
class QuietOutputListener implements ListenerInterface
{
    use InputDriverAwareTrait;
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
     * Clear all output formatters and add one that simple removes the output
     */
    public function onPowerUp()
    {
        if ($this->inputDriver->getArgumentsContainer()->isOptionProvided('quiet')) {
            foreach ($this->outputDriver->getFormatters() as $formatter) {
                $this->outputDriver->removeFormatter($formatter);
            }
            $this->outputDriver->addFormatter(new QuietFormatter());
        }
    }
}
