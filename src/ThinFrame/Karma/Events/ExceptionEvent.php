<?php
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
     * __construct
     *
     * @param \Exception $exception
     */
    public function __construct(\Exception $exception)
    {
        parent::__construct(self::EVENT_ID, ['exception' => $exception]);
    }

    /**
     * @return \Exception
     */
    public function getException()
    {
        return $this->getPayload()->get('exception')->get();
    }

}