<?php

namespace ThinFrame\Karma\Listeners;

use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use ThinFrame\Events\Dispatcher;
use ThinFrame\Events\DispatcherAwareInterface;
use ThinFrame\Events\ListenerInterface;
use ThinFrame\Karma\Events\ControllerActionEvent;
use ThinFrame\Karma\Events\ControllerResponseEvent;
use ThinFrame\Karma\Exceptions\Http\NotFoundHttpException;
use ThinFrame\Server\Events\HttpRequestEvent;
use ThinFrame\Server\Http\Request;
use ThinFrame\Server\Http\Response;

/**
 * Class RequestListener
 *
 * @package ThinFrame\Karma\Listeners
 * @since   0.2
 */
class RequestListener implements ListenerInterface, DispatcherAwareInterface
{
    /**
     * @var Dispatcher
     */
    private $dispatcher;

    /**
     * @var RouteCollection
     */
    private $routeCollection;

    /**
     * @param Dispatcher $dispatcher
     */
    public function setDispatcher(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param RouteCollection $routeCollection
     */
    public function setRouteCollection(RouteCollection $routeCollection)
    {
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
                'method' => 'onRequest'
            ]
        ];
    }

    /**
     * Handles request event
     *
     * @param HttpRequestEvent $event
     *
     * @throws NotFoundHttpException
     */
    public function onRequest(HttpRequestEvent $event)
    {
        $matcher = new UrlMatcher($this->routeCollection, new RequestContext('', $event->getRequest()->getMethod()));
        try {
            $route = $matcher->match($event->getRequest()->getPath());
            $this->handleRoute($route, $event->getRequest(), $event->getResponse());
            $event->stopPropagation();
        } catch (ResourceNotFoundException $exception) {
            throw new NotFoundHttpException;
        }
    }

    /**
     * @param          $route
     * @param Request  $request
     * @param Response $response
     */
    private function handleRoute($route, Request $request, Response $response)
    {
        $theRoute = $this->routeCollection->get($route['_route']);

        $controllerClass = $theRoute->getOption('karmaController');

        $action = $theRoute->getOption('karmaAction');

        $controller = new $controllerClass($this->dispatcher, $request, $response);
        /* @var $controller \ThinFrame\Karma\ViewController\AbstractController */

        unset($route['_route']);

        $this->dispatcher->trigger(
            $controllerActionEvent = new ControllerActionEvent(
                $request,
                $response,
                $controller,
                $action,
                $route
            )
        );

        $actionResponse = $controller->trigger($action, $controllerActionEvent->getArguments());

        $this->dispatcher->trigger(
            $controllerResponseEvent = new ControllerResponseEvent(
                $request,
                $response,
                $controller,
                $action,
                $controllerActionEvent->getArguments(),
                $actionResponse
            )
        );

        $response->setContent((string)$controllerResponseEvent->getActionResponse());
    }
}
