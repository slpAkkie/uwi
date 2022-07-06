<?php

namespace Uwi\Foundation\Http\Request;

use Uwi\Contracts\SingletonContract;

class Request implements SingletonContract
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
     * Calls when singleton has been instantiated and saved
     *
     * @return void
     */
    public function boot(): void
    {
        $this->userAgent = $_SERVER['HTTP_USER_AGENT'];
        $this->httpHost = $_SERVER['HTTP_HOST'];
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->uri = $_SERVER['REQUEST_URI'];
    }
}
