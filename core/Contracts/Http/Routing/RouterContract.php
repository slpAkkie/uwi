<?php

namespace Uwi\Contracts\Http\Routing;

use Uwi\Contracts\Container\SingletonContract;

interface RouterContract extends SingletonContract
{
    /**
     * Add new route to routes list.
     *
     * @param \Uwi\Contracts\Http\Routing\RouteContract $route
     * @return \Uwi\Contracts\Http\Routing\RouteContract
     */
    public function addRoute(\Uwi\Contracts\Http\Routing\RouteContract $route): \Uwi\Contracts\Http\Routing\RouteContract;

    /**
     * Returns current Route according to Request.
     *
     * @return \Uwi\Contracts\Http\Routing\RouteContract
     * 
     * @throws \Uwi\Contracts\Application\Exceptions\ExceptionContract
     */
    public function current(): \Uwi\Contracts\Http\Routing\RouteContract;
}
