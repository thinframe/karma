<?php

namespace ThinFrame\Karma\Events;

use ThinFrame\Events\AbstractEvent;

/**
 * Class ErrorEvent
 *
 * @package ThinFrame\Karma\Events
 * @since   0.1
 */
class ErrorEvent extends AbstractEvent
{

    const EVENT_ID = 'karma.error';

    /**
     * @param int    $number
     * @param string $message
     * @param string $file
     * @param int    $line
     */
    public function __construct($number, $message, $file, $line)
    {
        parent::__construct(
            self::EVENT_ID,
            [
                'number'  => $number,
                'message' => $message,
                'file'    => $file,
                'line'    => $line
            ]
        );
    }

    /**
     * @return int
     */
    public function getNumber()
    {
        return $this->getPayload()->get('number')->get();
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->getPayload()->get('message')->get();
    }

    /**
     * @return string
     */
    public function getFile()
    {
        return $this->getPayload()->get('file')->get();
    }

    /**
     * @return int
     */
    public function getLine()
    {
        return $this->getPayload()->get('line')->get();
    }
}
