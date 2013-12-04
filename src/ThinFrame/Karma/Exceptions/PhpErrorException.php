<?php
namespace ThinFrame\Karma\Exceptions;

use Exception;

/**
 * Class PhpErrorException
 *
 * @package ThinFrame\Karma\Exceptions
 * @since   0.1
 */
class PhpErrorException extends \Exception
{
    /**
     * @param string    $message
     * @param int       $code
     * @param string    $file
     * @param string    $line
     * @param Exception $previous
     */
    public function __construct($message = "", $code = 0, $file = '', $line = '', Exception $previous = null)
    {
        $this->file = $file;
        $this->line = $line;
        parent::__construct($message, $code, $previous);
    }
}