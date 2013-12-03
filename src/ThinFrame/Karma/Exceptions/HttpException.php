<?php

namespace ThinFrame\Karma\Exceptions;

use ThinFrame\Foundation\Exceptions\Exception;
use ThinFrame\Http\Constants\StatusCode;

/**
 * Class HttpException
 *
 * @package ThinFrame\Karma\Exceptions
 * @since   0.1
 */
class HttpException extends Exception implements HttpExceptionInterface
{
    /**
     * @var StatusCode
     */
    private $statusCode;

    /**
     * Constructor
     *
     * @param string     $message
     * @param StatusCode $code
     */
    public function __construct($message, StatusCode $code)
    {
        $this->statusCode = $code;
        parent::__construct($message, $code->__toString());
    }

    /**
     * Get exception status code
     *
     * @return StatusCode
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }
}