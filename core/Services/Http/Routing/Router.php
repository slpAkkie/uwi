<?php

namespace Uwi\Services\Http\Routing;

use Uwi\Contracts\Application\ApplicationContract;
use Uwi\Contracts\Application\Exceptions\ExceptionContract;
use Uwi\Contracts\Http\Request\RequestContract;
use Uwi\Contracts\Http\Routing\RouteContract;
use Uwi\Contracts\Http\Routing\RouterContract;
use Uwi\Foundation\Exceptions\Exception;

class Router implements RouterContract
{
    /**
     * List of available routes.
     *
     * @var array<string, \Uwi\Contracts\Http\Routing\RouteContract>
     */
    protected array $routes = [];

    /**
     * Instantiate Router.
     *
     * @param \Uwi\Contracts\Application\ApplicationContract $app
     * @param \Uwi\Contracts\Http\Request\RequestContract $request
     */
    public function __construct(
        protected ApplicationContract $app,
        protected RequestContract $request
    ) {
        //
    }

    /**
     * Add new route to routes list.
     *
     * @param \Uwi\Contracts\Http\Routing\RouteContract $route
     * @return \Uwi\Contracts\Http\Routing\RouteContract
     */
    public function addRoute(RouteContract $route): \Uwi\Contracts\Http\Routing\RouteContract
    {
        return $this->routes[] = $route;
    }

    /**
     * Returns current Route according to Request
     *
     * @return \Uwi\Contracts\Http\Routing\RouteContract
     * 
     * @throws \Uwi\Contracts\Application\Exceptions\ExceptionContract
     */
    public function current(): \Uwi\Contracts\Http\Routing\RouteContract
    {
        foreach ($this->routes as $route) {
            if ($route->url() === $this->request->url() && $route->method() === $this->request->method()) {
                return $route;
            }
        }

        throw new Exception("Route [{$this->request->url()}] not found", 404);
    }
}
