<?php

namespace Uwi\Services\Http\Routing;

use Exception;
use Uwi\Contracts\Http\Request\RequestContract;
use Uwi\Contracts\Http\Routing\RouteContract;
use Uwi\Contracts\Http\Routing\RouterContract;

class Router implements RouterContract
{
    /**
     * List of available routes.
     *
     * @var array<string, RouteContract>
     */
    protected array $routes = [];

    /**
     * Instantiate new Router.
     */
    public function __construct(
        protected RequestContract $request
    ) {
        //
    }

    /**
     * Add new route to routes list.
     *
     * @param RouteContract $route
     * @return RouteContract
     */
    public function addRoute(RouteContract $route): RouteContract
    {
        return $this->routes[] = $route;
    }

    /**
     * Returns current Route according to Request
     *
     * @return RouteContract
     * 
     * @throws Exception
     */
    public function current(): RouteContract
    {
        foreach ($this->routes as $route) {
            if ($route->url() === $this->request->url() && $route->method() === $this->request->method()) {
                return $route;
            }
        }

        throw new Exception("Route [{$this->request->url()}] not found");
    }
}
