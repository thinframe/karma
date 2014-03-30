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
     * @var \ReflectionClass
     */
    private $reflection;

    /**
     * @var Router
     */
    private $router;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->reflection = new \ReflectionClass($this);
    }

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

        $parameters = [];

        foreach ($this->reflection->getMethod($method)->getParameters() as $parameter) {
            switch ($parameter->getName()) {
                case 'request':
                    $parameters[] = $request;
                    break;
                case 'response':
                    $parameters[] = $response;
                    break;
                default:
                    if (isset($arguments[$parameter->getName()])) {
                        $parameters[] = $arguments[$parameter->getName()];
                    } else {
                        $parameters[] = null;
                    }
            }
        }

        return call_user_func_array([$this, $method], $parameters);
    }

    /**
     * Set the router
     *
     * @param Router $router
     *
     * @return $this
     */
    public function setRouter(Router $router)
    {
        $this->router = $router;

        return $this;
    }

    /**
     * Generate url
     *
     * @param string $routeName
     * @param array  $routeParams
     *
     * @return string
     */
    public function generateUrl($routeName, array $routeParams = [])
    {
        return $this->router->generateUrl($routeName, $routeParams);
    }
}
