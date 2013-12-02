<?php

/**
 * /src/ThinFrame/Karma/Events/ExceptionEvent.php
 *
 * @copyright 2013 Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Karma\Events;

use ThinFrame\Events\AbstractEvent;

/**
 * Class ExceptionEvent
 *
 * @package ThinFrame\Karma\Events
 * @since   0.1
 */
class ExceptionEvent extends AbstractEvent
{
    const EVENT_ID = 'karma.exception';

    /**
     * Construct
     *
     * @param \Exception $exception
     */
    public function __construct(\Exception $exception)
    {
        parent::__construct(self::EVENT_ID, ['exception' => $exception]);
    }

    /**
     * Get Exception
     *
     * @return \Exception
     */
    public function getException()
    {
        return $this->getPayload()->get('exception')->get();
    }

}