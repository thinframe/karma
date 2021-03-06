<?php

/**
 * src/Exceptions/Http/NotFoundHttpException.php
 *
 * @author    Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Karma\Exceptions\Http;

use Exception;
use ThinFrame\Http\Constants\StatusCode;
use ThinFrame\Server\Exceptions\AbstractHttpException;

/**
 * Class NotFoundHttpException
 *
 * @package ThinFrame\Karma\Exceptions\Http
 * @since   0.2
 */
class NotFoundHttpException extends AbstractHttpException
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
