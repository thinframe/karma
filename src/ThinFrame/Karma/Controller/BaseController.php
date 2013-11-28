<?php

namespace ThinFrame\Karma\Controller;

use ThinFrame\Events\Dispatcher;
use ThinFrame\Events\DispatcherAwareInterface;
use ThinFrame\Http\Foundation\RequestInterface;
use ThinFrame\Http\Foundation\ResponseInterface;

/**
 * Class BaseController
 *
 * @package ThinFrame\Karma\Controller
 * @since   0.1
 */
class BaseController implements DispatcherAwareInterface
{
    /**
     * @var RequestInterface
     */
    protected $request;
    /**
     * @var ResponseInterface
     */
    protected $response;
    /**
     * @var Dispatcher
     */
    protected $dispatcher;

    /**
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     */
    final public function __construct(RequestInterface $request, ResponseInterface $response)
    {
        $this->request  = $request;
        $this->response = $response;
    }

    /**
     * @param Dispatcher $dispatcher
     */
    public function setDispatcher(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }
}