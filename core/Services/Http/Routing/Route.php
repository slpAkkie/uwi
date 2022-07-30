<?php

namespace Uwi\Services\Http\Routing;

use Uwi\Contracts\Http\Routing\RouteContract;

class Route implements RouteContract
{
    /**
     * Instantiate Route.
     *
     * @param string $method
     * @param string $url
     * @param string $controller
     * @param string $action
     */
    public function __construct(
        protected string $method,
        protected string $url,
        protected string $controller,
        protected string $action,
    ) {
        //
    }

    /**
     * Returns route's method.
     *
     * @return string
     */
    public function method(): string
    {
        return $this->method;
    }

    /**
     * Returns route's url.
     *
     * @return string
     */
    public function url(): string
    {
        return $this->url;
    }

    /**
     * Returns route's action.
     *
     * @return array<string>
     */
    public function action(): array
    {
        return [$this->controller, $this->action];
    }
}
