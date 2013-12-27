<?php

namespace ThinFrame\Karma\Helpers;


use ThinFrame\Pcntl\Process;

/**
 * Class ServerHelper
 *
 * @package ThinFrame\Karma\Helpers
 * @since   0.2
 */
class ServerHelper
{
    const PID = 'app/pid/server.pid';

    /**
     * Save current process PID
     */
    public static function savePID()
    {
        @file_put_contents(self::PID, getmypid());
    }

    /**
     * Get server PID
     *
     * @return bool|int
     */
    public static function getServerPID()
    {
        $pid = @file_get_contents(self::PID);
        if (trim($pid) == '') {
            return false;
        }
        return intval($pid);
    }

    /**
     * Check if server is running
     *
     * @return bool
     */
    public static function isRunning()
    {
        try {
            $process = new Process(self::getServerPID());
            return $process->isAlive();
        } catch (\Exception $e) {
            return false;
        }
    }
}
