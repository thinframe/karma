<?php

/**
 * /src/ThinFrame/Karma/Listeners/RouteResolverListener.php
 *
 * @copyright 2013 Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Karma\Listeners;

use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use ThinFrame\Events\Constants\Priority;
use ThinFrame\Events\Dispatcher;
use ThinFrame\Events\DispatcherAwareInterface;
use ThinFrame\Events\ListenerInterface;
use ThinFrame\Karma\Events\ActionResponseEvent;
use ThinFrame\Karma\Events\RequestArgumentsEvent;
use ThinFrame\Server\Events\HttpRequestEvent;
use ThinFrame\Server\HttpRequest;
use ThinFrame\Server\HttpResponse;

/**
 * Class RouteResolverListener
 *
 * @package ThinFrame\Karma\Listeners
 * @since   0.1
 */
class RouteResolverListener implements ListenerInterface, DispatcherAwareInterface
{

    /**
     * @var RouteCollection
     */
    private $routesCollection;
    /**
     * @var Dispatcher
     */
    private $dispatcher;

    /**
     * Constructor
     *
     * @param RouteCollection $routeCollection
     */
    public function __construct(RouteCollection $routeCollection)
    {
        $this->routesCollection = $routeCollection;
    }

    /**
     * Attach dispatcher
     *
     * @param Dispatcher $dispatcher
     */
    public function setDispatcher(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Get event mappings ["event"=>["method"=>"methodName","priority"=>1]]
     *
     * @return array
     */
    public function getEventMappings()
    {
        return [HttpRequestEvent::EVENT_ID => ['method' => 'onRequest', 'priority' => Priority::LOW]];
    }

    /**
     * Handle http request
     *
     * @param HttpRequestEvent $event
     *
     * @throws \Exception
     */
    public function onRequest(HttpRequestEvent $event)
    {
        $request  = $event->getRequest();
        $response = $event->getResponse();

        $requestContext = new RequestContext(
            '',
            $request->getMethod()
        );

        $matcher = new UrlMatcher($this->routesCollection, $requestContext);

        try {
            $route = $matcher->match($request->getPath());
            $this->handleRoute($route, $request, $response);
            $event->stopPropagation();
        } catch (ResourceNotFoundException $e) {
            //do nothing
        } catch (\Exception $e) {
            //send it up into the chain
            throw $e;
        }
    }

    /**
     * Handle route
     *
     * @param              $route
     * @param HttpRequest  $request
     * @param HttpResponse $response
     */
    public function handleRoute($route, HttpRequest $request, HttpResponse $response)
    {
        $symfonyRoute = $this->routesCollection->get($route['_route']);

        $controllerName = $symfonyRoute->getOption('karmaController');

        $controller = new $controllerName($request, $response);

        unset($route['_route']);

        //trigger request arguments filter
        $requestArgumentsFilter = new RequestArgumentsEvent(
            $request,
            $response,
            $route,
            $controller,
            $symfonyRoute->getOption('karmaMethod')
        );

        $this->dispatcher->trigger($requestArgumentsFilter);

        //trigger controller action
        $actionResponse = call_user_func_array(
            [$controller, $symfonyRoute->getOption('karmaMethod')],
            $requestArgumentsFilter->getArguments()
        );

        //trigger action response filter
        $actionResponseFilter = new ActionResponseEvent(
            $request,
            $response,
            $actionResponse,
            $controller,
            $symfonyRoute->getOption('karmaMethod')
        );

        $this->dispatcher->trigger($actionResponseFilter);

        //dispatch response
        $response->addContent((string)$actionResponseFilter->getActionResponse());
        $response->end();
    }
}
