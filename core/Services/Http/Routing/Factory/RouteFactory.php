<?php

namespace Uwi\Services\Http\Routing\Factory;

use Exception;
use Uwi\Contracts\Application\ApplicationContract;
use Uwi\Contracts\Http\Routing\RouteContract;
use Uwi\Contracts\Http\Routing\RouterContract;

class RouteFactory
{
    /**
     * Available HTTP methods to create Route for.
     */
    public const HTTP_METHODS = [
        'HEAD', 'GET', 'POST', 'PUT', 'PATCH', 'DELETE',
    ];

    /**
     * Instantiate RouteFactory.
     *
     * @param ApplicationContract $app
     * @param RouterContract $router
     */
    public function __construct(
        protected ApplicationContract $app,
        protected RouterContract $router,
    ) {
        //
    }

    /**
     * Create new Route and push it to Router.
     *
     * @param string $url
     * @param string $method
     * @param array<string> $action
     * @return RouteContract
     */
    public function createRoute(string $method, string $url, array $action): RouteContract
    {
        $route = $this->app->make(RouteContract::class, $method, $url, ...$action);
        $this->router->addRoute($route);

        return $route;
    }

    /**
     * A handler for dynamically creating a route.
     *
     * @param string $method
     * @param array<string> $args
     * @return mixed
     */
    public function __call($method, $args): mixed
    {
        if (in_array(strtoupper($method), static::HTTP_METHODS)) {
            return $this->createRoute(strtoupper($method), ...$args);
        }

        throw new Exception("HTTP method [$method] isn't supported");
    }
}
