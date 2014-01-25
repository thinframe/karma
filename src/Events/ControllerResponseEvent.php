<?php

/**
 * src/Events/ControllerResponseEvent.php
 *
 * @author    Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Karma\Events;

use ThinFrame\Events\AbstractEvent;
use ThinFrame\Karma\ViewController\AbstractController;
use ThinFrame\Server\Http\Request;
use ThinFrame\Server\Http\Response;

/**
 * Class ControllerResponseEvent
 *
 * @package ThinFrame\Karma\Events
 * @since   0.2
 */
class ControllerResponseEvent extends AbstractEvent
{
    const  EVENT_ID = 'thinframe.karma.controller.response';

    /**
     * Constructor
     *
     * @param Request            $request
     * @param Response           $response
     * @param AbstractController $controller
     * @param string             $actionName
     * @param array              $arguments
     * @param mixed              $actionResponse
     */
    public function __construct(
        Request $request,
        Response $response,
        AbstractController $controller,
        $actionName,
        array $arguments = [],
        $actionResponse
    ) {
        parent::__construct(
            self::EVENT_ID,
            [
                'request'        => $request,
                'response'       => $response,
                'controller'     => $controller,
                'actionName'     => $actionName,
                'arguments'      => $arguments,
                'actionResponse' => $actionResponse
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
     * Get action response
     *
     * @return mixed
     */
    public function getActionResponse()
    {
        return $this->getPayload()->get('actionResponse')->getOrElse(null);
    }

    /**
     * @param $response
     */
    public function setActionResponse($response)
    {
        $this->getPayload()->set('actionResponse', $response);
    }
}
