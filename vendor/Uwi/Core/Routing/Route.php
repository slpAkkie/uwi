<?php

namespace Uwi\Core\Routing;

class Route
{
    /**
     * HTTP method
     *
     * @var string
     */
    public readonly string $httpMethod;

    /**
     * URI
     *
     * @var string
     */
    public readonly string $uri;

    /**
     * Controller class
     *
     * @var string
     */
    public readonly string $controllerClass;

    /**
     * Controller method
     *
     * @var string
     */
    public readonly string $controllerMethod;

    /**
     * Route name
     *
     * @var string|null
     */
    public ?string $name;

    /**
     * Instantiate new Route
     *
     * @param string $method
     * @param string $uri
     * @param array $action
     */
    public function __construct(string $method, string $uri, array $action)
    {
        // Save route data
        $this->method = $method;
        $this->uri = $uri;

        $this->controllerClass = $action[0];
        $this->controllerMethod = $action[1];
    }

    /**
     * Set name for the route
     *
     * @param string $name
     * @return self
     */
    public function name(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
