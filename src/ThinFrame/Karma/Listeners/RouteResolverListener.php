<?php
namespace ThinFrame\Karma\Listeners;

use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use ThinFrame\Events\Constants\Priority;
use ThinFrame\Events\Dispatcher;
use ThinFrame\Events\DispatcherAwareInterface;
use ThinFrame\Events\ListenerInterface;
use ThinFrame\Events\SimpleEvent;
use ThinFrame\Karma\Events\ActionResponseEvent;
use ThinFrame\Karma\Events\RequestArgumentsEvent;
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
        return ['thinframe.http.inbound_request' => ['method' => 'onRequest', 'priority' => Priority::LOW]];
    }

    /**
     * Handle thinframe.http.inbount_request event
     *
     * @param SimpleEvent $event
     */
    public function onRequest(SimpleEvent $event)
    {
        $request  = $event->getPayload()->get('request')->get();
        $response = $event->getPayload()->get('response')->get();
        /* @var $request \ThinFrame\Server\HttpRequest */
        /* @var $response \ThinFrame\Server\HttpResponse */

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