<?php

namespace Uwi\Contracts\Http\Routing;

interface RouteContract
{
    /**
     * Instantiate Route.
     *
     * @param string $method
     * @param string $url
     * @param string $controller
     * @param string $action
     */
    public function __construct(string $method, string $url, string $controller, string $action);

    /**
     * Returns route's method.
     *
     * @return string
     */
    public function method(): string;


    /**
     * Returns route's url.
     *
     * @return string
     */
    public function url(): string;


    /**
     * Returns route's action.
     *
     * @return array<string>
     */
    public function action(): array;
}
