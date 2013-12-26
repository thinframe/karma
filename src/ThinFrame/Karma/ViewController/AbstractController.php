<?php

namespace ThinFrame\Karma\ViewController;

use ThinFrame\Events\Dispatcher;
use ThinFrame\Karma\Exceptions\Http\NotFoundHttpException;
use ThinFrame\Server\Http\Request;
use ThinFrame\Server\Http\Response;

/**
 * Class AbstractController
 *
 * @package ThinFrame\Karma\Controller
 */
abstract class AbstractController
{
    /**
     * @var Dispatcher
     */
    private $dispatcher;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var Response
     */
    private $response;

    /**
     * Constructor
     *
     * @param Dispatcher $dispatcher
     * @param Request    $request
     * @param Response   $response
     */
    public function __construct(Dispatcher $dispatcher, Request $request, Response $response)
    {
        $this->dispatcher = $dispatcher;
        $this->response   = $response;
        $this->request    = $request;
    }


    /**
     * Trigger controller action
     *
     * @param string $action
     * @param array  $arguments
     *
     * @return mixed
     * @throws \ThinFrame\Karma\Exceptions\Http\NotFoundHttpException
     */
    public function trigger($action, array $arguments = [])
    {
        if (is_callable([$this, $action])) {
            return call_user_func_array([$this, $action], $arguments);
        }
        throw new NotFoundHttpException('Cannot trigger action ' . __CLASS__ . '::' . $action);
    }
}
