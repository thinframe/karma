<?php

namespace ThinFrame\Karma\Controller;

use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use ThinFrame\Events\DispatcherAwareTrait;
use ThinFrame\Http\Foundation\RequestInterface;
use ThinFrame\Http\Foundation\ResponseInterface;
use ThinFrame\Server\Exception\NotFoundHttpException;

/**
 * Class AbstractController
 *
 * @package ThinFrame\Karma\Controller
 * @since   0.3
 */
abstract class AbstractController
{
    use DispatcherAwareTrait;
    use ContainerAwareTrait;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * Trigger method
     *
     * @param string            $method
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array             $arguments
     *
     * @return mixed
     *
     * @throws NotFoundHttpException
     */
    public function triggerMethod(
        $method,
        RequestInterface $request,
        ResponseInterface $response,
        array $arguments = []
    ) {
        if (!method_exists($this, $method)) {
            throw new NotFoundHttpException;
        }
        $this->request  = $request;
        $this->response = $response;

        return call_user_func_array([$this, $method], $arguments);
    }
}
