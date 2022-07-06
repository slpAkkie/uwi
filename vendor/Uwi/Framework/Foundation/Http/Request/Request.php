<?php

namespace Uwi\Foundation\Http\Request;

class Request
{
    /**
     * User agent
     *
     * @var string
     */
    public readonly string $userAgent;

    /**
     * HTTP host
     *
     * @var string
     */
    public readonly string $httpHost;

    /**
     * Request method
     *
     * @var string
     */
    public readonly string $method;

    /**
     * Request URI
     *
     * @var string
     */
    public readonly string $uri;

    /**
     * Initialize the Request
     */
    public function __construct()
    {
        $this->userAgent = $_SERVER['HTTP_USER_AGENT'];
        $this->httpHost = $_SERVER['HTTP_HOST'];
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->uri = $_SERVER['REQUEST_URI'];
    }
}
