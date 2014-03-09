<?php

namespace ThinFrame\Karma\Listener;

use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use ThinFrame\Events\DispatcherAwareTrait;
use ThinFrame\Events\ListenerInterface;
use ThinFrame\Http\Foundation\RequestInterface;
use ThinFrame\Http\Foundation\ResponseInterface;
use ThinFrame\Karma\Controller\Router;
use ThinFrame\Karma\Event\ControllerActionEvent;
use ThinFrame\Karma\Event\ControllerResponseEvent;
use ThinFrame\Server\Event\HttpRequestEvent;
use ThinFrame\Server\Exception\NotFoundHttpException;

/**
 * Class RequestListener
 *
 * @package ThinFrame\Karma\Listener
 * @since   0.3
 */
class RequestListener implements ListenerInterface
{
    use DispatcherAwareTrait;

    /**
     * @var Router
     */
    private $router;

    /**
     * @var RouteCollection
     */
    private $routeCollection;

    /**
     * Constructor
     *
     * @param Router          $router
     * @param RouteCollection $routeCollection
     */
    public function __construct(Router $router, RouteCollection $routeCollection)
    {
        $this->router          = $router;
        $this->routeCollection = $routeCollection;
    }

    /**
     * Get event mappings ["event"=>["method"=>"methodName","priority"=>1]]
     *
     * @return array
     */
    public function getEventMappings()
    {
        return [
            HttpRequestEvent::EVENT_ID => [
                'method' => 'onHttpRequest'
            ]
        ];
    }

    /**
     * @param HttpRequestEvent $event
     *
     * @throws NotFoundHttpException
     */
    public function onHttpRequest(HttpRequestEvent $event)
    {
        $event->getResponse()->getHeaders()->set('X-Powered-By', 'ThinFrame/Karma (ReactPHP Engine)');

        $requestContext = new RequestContext('', $event->getRequest()->getMethod());
        $urlMatcher     = new UrlMatcher($this->routeCollection, $requestContext);

        try {
            $route = $urlMatcher->match($event->getRequest()->getPath());
            $this->dispatchRoute($route, $event->getRequest(), $event->getResponse());
        } catch (ResourceNotFoundException $e) {
            throw new NotFoundHttpException;
        }
    }

    /**
     * Dispatch a route
     *
     * @param array             $routeDetails
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     */
    private function dispatchRoute(array $routeDetails, RequestInterface $request, ResponseInterface $response)
    {
        $route = $this->routeCollection->get($routeDetails['_route']);
        unset($routeDetails['_route']);
        unset($routeDetails['_controller']);
        unset($routeDetails['_method']);
        $controller = $this->router->getController($route->getDefault('_controller'));
        $preEvent   = new ControllerActionEvent(
            $controller,
            $route->getDefault('_method'),
            $request,
            $response,
            $routeDetails
        );
        $this->dispatcher->trigger($preEvent);
        $result    = $controller->triggerMethod(
            $preEvent->getActionName(),
            $request,
            $response,
            $preEvent->getArguments()
        );
        $postEvent = new ControllerResponseEvent(
            $controller,
            $preEvent->getActionName(),
            $request,
            $response,
            $preEvent->getArguments(),
            $result
        );
        $this->dispatcher->trigger($postEvent);
        $response->setContent((string)$postEvent->getActionResult());
    }
}
