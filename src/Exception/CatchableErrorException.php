<?php

namespace ThinFrame\Karma\Exception;

use ThinFrame\Foundation\Exception\RuntimeException;

/**
 * Class CatchableErrorException
 *
 * @package ThinFrame\Karma\Exception
 * @since   0.3
 */
class CatchableErrorException extends RuntimeException
{
    /**
     * Constructor
     *
     * @param string     $message
     * @param int        $code
     * @param string     $file
     * @param string     $line
     * @param \Exception $previous
     */
    public function __construct($message = "", $code = 0, $file = '', $line = '', \Exception $previous = null)
    {
        $this->file = $file;
        $this->line = $line;
        parent::__construct($message, $code, $previous);
    }
}
