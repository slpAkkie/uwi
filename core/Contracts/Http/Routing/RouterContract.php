<?php

namespace Uwi\Contracts\Http\Routing;

use Uwi\Contracts\Container\SingletonContract;

interface RouterContract extends SingletonContract
{
    /**
     * Add new route to routes list.
     *
     * @param RouteContract $route
     * @return RouteContract
     */
    public function addRoute(RouteContract $route): RouteContract;

    /**
     * Returns current Route according to Request
     *
     * @return RouteContract
     * 
     * @throws Exception
     */
    public function current(): RouteContract;
}
