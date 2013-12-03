<?php

namespace ThinFrame\Karma\Exceptions\Http;

use Exception;
use ThinFrame\Http\Constants\StatusCode;
use ThinFrame\Server\Exceptions\AbstractHttpException;

/**
 * Class InternalServerErrorException
 *
 * @package ThinFrame\Karma\Exceptions\Http
 * @since   0.1
 */
class InternalServerErrorException extends AbstractHttpException
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
            new StatusCode(StatusCode::INTERNAL_SERVER_ERROR),
            $message,
            $previous
        );
    }
}
