<?php

/**
 * src/Listeners/MonitorListener.php
 *
 * @author    Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Karma\Listeners;

use ThinFrame\CommandLine\DependencyInjection\OutputDriverAwareTrait;
use ThinFrame\Events\ListenerInterface;
use ThinFrame\Pcntl\Helpers\Exec;

/**
 * Class MonitorListener
 *
 * @package ThinFrame\Karma\Listeners
 * @since   0.2
 */
class MonitorListener implements ListenerInterface
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
            'thinframe.inotify' => [
                'method' => 'onChange'
            ]
        ];
    }

    public function onChange()
    {
        $this->outputDriver->send(
            '[format foreground="green" background="black" effects="bold"]' .
            '[sideways] File system changes %MIDDLE% [ DETECTED ] [/sideways][/format]' . PHP_EOL
        );
        $this->outputDriver->send(
            '[format foreground="green" background="black" effects="bold"]' .
            '[sideways] Server status %MIDDLE% [ RESTARTING ] [/sideways][/format]' . "\r"
        );
        Exec::viaPipe('bin/thinframe server restart', KARMA_ROOT);

        $this->outputDriver->send(
            '[format foreground="green" background="black" effects="bold"]' .
            '[sideways] Server status %MIDDLE% [ RESTARTED ] [/sideways][/format]' . PHP_EOL
        );

    }
}