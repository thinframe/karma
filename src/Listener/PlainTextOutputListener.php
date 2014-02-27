<?php

namespace ThinFrame\Karma\Listener;

use ThinFrame\CommandLine\IO\Formatters\ShortCodesFormatter;
use ThinFrame\CommandLine\IO\InputDriverAwareTrait;
use ThinFrame\CommandLine\IO\OutputDriverAwareTrait;
use ThinFrame\Events\Constants\Priority;
use ThinFrame\Events\ListenerInterface;
use ThinFrame\Foundation\Helpers\ShortCodesProcessor;
use ThinFrame\Karma\IO\Formatter\PlainTextShortCodeFormatter;

/**
 * Class PlainTestOutputListener
 *
 * @package ThinFrame\Karma\Listener
 * @since   0.3
 */
class PlainTextOutputListener implements ListenerInterface
{
    use InputDriverAwareTrait;
    use OutputDriverAwareTrait;

    /**
     * @var ShortCodesProcessor
     */
    private $shortCodesProcessor;

    /**
     * Constructor
     *
     * @param ShortCodesProcessor $processor
     */
    public function __construct(ShortCodesProcessor $processor)
    {
        $this->shortCodesProcessor = $processor;
    }

    /**
     * Get event mappings ["event"=>["method"=>"methodName","priority"=>1]]
     *
     * @return array
     */
    public function getEventMappings()
    {
        return [
            'power_up' => [
                'method'   => 'onPowerUp',
                'priority' => Priority::CRITICAL
            ]
        ];
    }

    /**
     * Replaces the shortcodes formatter if it exists and add a new one that just
     * removes the shortcodes tags
     */
    public function onPowerUp()
    {
        if ($this->inputDriver->getArgumentsContainer()->isOptionProvided('plain-text')) {
            foreach ($this->outputDriver->getFormatters() as $formatter) {
                if ($formatter instanceof ShortCodesFormatter) {
                    $this->outputDriver->removeFormatter($formatter);
                }
            }

            $plainTextFormatter = new PlainTextShortCodeFormatter($this->shortCodesProcessor);
            $this->outputDriver->addFormatter($plainTextFormatter);

        }
    }
}