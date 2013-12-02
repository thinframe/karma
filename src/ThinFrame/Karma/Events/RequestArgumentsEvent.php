<?php
namespace ThinFrame\Karma\Events;

use ThinFrame\Events\AbstractEvent;
use ThinFrame\Karma\Controller\BaseController;
use ThinFrame\Server\HttpRequest;
use ThinFrame\Server\HttpResponse;

/**
 * Class RequestArgumentsEvent
 *
 * @package ThinFrame\Karma\Events
 * @since   0.1
 */
class RequestArgumentsEvent extends AbstractEvent
{
    const EVENT_ID = 'thinframe.karma.request_arguments';

    /**
     * Constructor
     *
     * @param HttpRequest    $request
     * @param HttpResponse   $response
     * @param array          $arguments
     * @param BaseController $controller
     * @param string         $action
     */
    public function __construct(
        HttpRequest $request,
        HttpResponse $response,
        array $arguments,
        BaseController $controller,
        $action
    ) {
        parent::__construct(
            self::EVENT_ID,
            [
                'request'    => $request,
                'response'   => $response,
                'arguments'  => $arguments,
                'controller' => $controller,
                'action'     => $action
            ]
        );
    }

    /**
     * Get request object
     *
     * @return HttpRequest
     */
    public function getRequest()
    {
        return $this->getPayload()->get('request')->get();
    }

    /**
     * Get response object
     *
     * @return HttpResponse
     */
    public function getResponse()
    {
        return $this->getPayload()->get('response')->get();
    }

    /**
     * Get request arguments
     *
     * @return array
     */
    public function getArguments()
    {
        return $this->getPayload()->get('arguments')->get();
    }

    /**
     * Get controller instance
     *
     * @return BaseController
     */
    public function getController()
    {
        return $this->getPayload()->get('controller')->get();
    }

    /**
     * Get request action
     *
     * @return string
     */
    public function getAction()
    {
        return $this->getPayload()->get('action')->get();
    }

}