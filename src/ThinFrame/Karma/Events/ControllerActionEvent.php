<?php

namespace ThinFrame\Karma\Events;

use ThinFrame\Events\AbstractEvent;
use ThinFrame\Karma\ViewController\AbstractController;
use ThinFrame\Server\Http\Request;
use ThinFrame\Server\Http\Response;

/**
 * Class ControllerEvent
 *
 * @package ThinFrame\Karma\Events
 * @since   0.2
 */
class ControllerActionEvent extends AbstractEvent
{
    const  EVENT_ID = 'thinframe.karma.controller.action';

    /**
     * Constructor
     *
     * @param Request            $request
     * @param Response           $response
     * @param AbstractController $controller
     * @param string             $actionName
     * @param array              $arguments
     */
    public function __construct(
        Request $request,
        Response $response,
        AbstractController $controller,
        $actionName,
        array $arguments = []
    ) {
        parent::__construct(
            self::EVENT_ID,
            [
                'request'    => $request,
                'response'   => $response,
                'controller' => $controller,
                'actionName' => $actionName,
                'arguments'  => $arguments
            ]
        );
    }

    /**
     * Get request object
     *
     * @return Request
     */
    public function getRequest()
    {
        return $this->getPayload()->get('request')->get();
    }

    /**
     * Get response object
     *
     * @return Response
     */
    public function getResponse()
    {
        return $this->getPayload()->get('response')->get();
    }

    /**
     * Get controller instance
     *
     * @return AbstractController
     */
    public function getController()
    {
        return $this->getPayload()->get('controller')->get();
    }

    /**
     * Get action name
     *
     * @return string
     */
    public function getActionName()
    {
        return $this->getPayload()->get('actionName')->get();
    }

    /**
     * Get action arguments
     *
     * @return array
     */
    public function getArguments()
    {
        return $this->getPayload()->get('arguments')->getOrElse([]);
    }

    /**
     * @param array $arguments
     *
     * @return $this
     */
    public function setArguments(array $arguments)
    {
        $this->getPayload()->set('arguments', $arguments);
        return $this;
    }
}
