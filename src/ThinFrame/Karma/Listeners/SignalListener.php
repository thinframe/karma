<?php

/**
 * src/ThinFrame/Karma/Listeners/SignalListener.php
 *
 * @copyright 2013 Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Karma\Listeners;

use React\EventLoop\LoopInterface;
use ThinFrame\Events\ListenerInterface;
use ThinFrame\Pcntl\Constants\Signal;
use ThinFrame\Pcntl\PcntlSignalEvent;
use ThinFrame\Pcntl\SignalsCatcher;

/**
 * Class SignalListener
 *
 * @package ThinFrame\Karma
 * @since   0.1
 */
class SignalListener implements ListenerInterface
{

    /**
     * @var SignalsCatcher
     */
    private $signalCatcher;
    /**
     * @var LoopInterface
     */
    private $loop;

    /**
     * Constructor
     *
     * @param SignalsCatcher $signalCatcher
     * @param LoopInterface  $loop
     */
    public function __construct(SignalsCatcher $signalCatcher, LoopInterface $loop)
    {
        $this->signalCatcher = $signalCatcher;
        $this->loop          = $loop;
        //TODO: uncomment when issue resolved https://github.com/reactphp/react/issues/221
//        $this->signalCatcher->unbind();
//        $this->signalCatcher->addSignal(new Signal(Signal::KEYBOARD_INTERRUPT));
//        $this->signalCatcher->bind();
    }

    /**
     * Get event mappings ["event"=>["method"=>"methodName","priority"=>1]]
     *
     * @return array
     */
    public function getEventMappings()
    {
        return [
            PcntlSignalEvent::EVENT_ID => [
                'method' => 'onPcntlSignal'
            ]
        ];
    }

    /**
     * Handle pcntl signal
     *
     * @param PcntlSignalEvent $event
     */
    public function onPcntlSignal(PcntlSignalEvent $event)
    {
        if ($event->getSignal()->equals(Signal::KEYBOARD_INTERRUPT)) {
            $this->loop->stop();
        }
    }
}
