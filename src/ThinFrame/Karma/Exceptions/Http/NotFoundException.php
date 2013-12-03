<?php

namespace ThinFrame\Karma\Exceptions\Http;

use Exception;
use ThinFrame\Http\Constants\StatusCode;
use ThinFrame\Server\Exceptions\AbstractHttpException;

/**
 * Class NotFoundException
 *
 * @package ThinFrame\Karma\Exceptions\Http
 * @since   0.1
 */
class NotFoundException extends AbstractHttpException
{
    /**
     * Constructor
     *
     * @param string    $message
     * @param Exception $previous
     */
    public function __construct($message = "", Exception $previous = null)
    {
        parent::__construct(
            new StatusCode(StatusCode::NOT_FOUND),
            $message,
            $previous
        );
    }
}
