<?php

namespace ThinFrame\Karma\Exceptions;

use ThinFrame\Http\Constants\StatusCode;

/**
 * Class NotFoundException
 *
 * @package ThinFrame\Karma\Exceptions
 * @since   0.1
 */
class NotFoundException extends HttpException
{
    /**
     * Constructor
     *
     * @param string $message
     */
    public function __construct($message)
    {
        parent::__construct($message, new StatusCode(StatusCode::NOT_FOUND));
    }

}