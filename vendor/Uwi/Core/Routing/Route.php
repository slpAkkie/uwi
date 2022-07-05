<?php

namespace Uwi\Core\Routing;

use Uwi\Core\App;

class Route
{
    /**
     * HTTP method
     *
     * @var string
     */
    private string $httpMethod;

    /**
     * URL
     *
     * @var string
     */
    private string $url;

    /**
     * Controller class
     *
     * @var string
     */
    private string $controllerClass;

    /**
     * Controller method
     *
     * @var string
     */
    private string $controllerMethod;

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
     * @param string $url
     * @param array $action
     */
    public function __construct(string $method, string $url, array $action)
    {
        // Save route data
        $this->method = $method;
        $this->url = $url;

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
