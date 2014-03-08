<?php

namespace ThinFrame\Karma\Event;

use ThinFrame\Events\SimpleEvent;
use ThinFrame\Http\Foundation\RequestInterface;
use ThinFrame\Http\Foundation\ResponseInterface;
use ThinFrame\Karma\Controller\AbstractController;

/**
 * Class ControllerActionEvent
 *
 * @package ThinFrame\Karma\Event
 * @since   0.3
 */
class ControllerActionEvent extends SimpleEvent
{
    const EVENT_ID = 'controller_action';

    /**
     * Construct
     *
     * @param AbstractController $controller
     * @param string             $actionName
     * @param RequestInterface   $request
     * @param ResponseInterface  $response
     * @param array              $arguments
     */
    public function __construct(
        AbstractController $controller,
        $actionName,
        RequestInterface $request,
        ResponseInterface $response,
        $arguments
    ) {
        parent::__construct(
            self::EVENT_ID,
            [
                'controller' => $controller,
                'actionName' => $actionName,
                'request'    => $request,
                'response'   => $response,
                'arguments'  => $arguments
            ]
        );
    }

    /**
     * Get controller
     *
     * @return AbstractController|null
     */
    public function getController()
    {
        return $this->getPayload()->get('controller')->getOrElse(null);
    }

    /**
     * Get action name
     *
     * @return string|null
     */
    public function getActionName()
    {
        return $this->getPayload()->get('actionName')->getOrElse(null);
    }

    /**
     * Get request
     *
     * @return RequestInterface|null
     */
    public function getRequest()
    {
        return $this->getPayload()->get('request')->getOrElse(null);
    }

    /**
     * Get response
     *
     * @return ResponseInterface|null
     */
    public function getResponse()
    {
        return $this->getPayload()->get('response')->getOrElse(null);
    }

    /**
     * Get arguments
     *
     * @return array
     */
    public function getArguments()
    {
        return $this->getPayload()->get('arguments')->getOrElse([]);
    }
}
