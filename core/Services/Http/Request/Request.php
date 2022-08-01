<?php

namespace Uwi\Services\Http\Request;

use Uwi\Contracts\Http\Request\RequestContract;

class Request implements RequestContract
{
    /**
     * Returns all cookie from the request or specified by key.
     *
     * @param string|null $key
     * @param string|null $default
     * @return array<string, string>|string
     */
    public function cookie(string|null $key = null, string|null $default = null): array|string
    {
        if (!is_null($key)) {
            return key_exists($key, $_COOKIE) ? $_COOKIE[$key] : $default;
        }

        return $_COOKIE;
    }

    /**
     * Returns entry of Accept HTTP Header.
     *
     * @return string
     */
    public function accept(): string
    {
        return $_SERVER['HTTP_ACCEPT'];
    }


    /**
     * Returns request scheme.
     *
     * @return string
     */
    public function scheme(): string
    {
        return key_exists('HTTPS', $_SERVER) ? 'https' : 'http';
    }

    /**
     * Returns HTTP host.
     *
     * @return string
     */
    public function host(): string
    {
        return $_SERVER['HTTP_HOST'];
    }


    /**
     * Returns request host with its sheme.
     *
     * @return string
     */
    public function fullHost(): string
    {
        return $this->scheme() . '://' . $this->host();
    }

    /**
     * Returns true if connection is under HTTPS protocol.
     *
     * @return boolean
     */
    public function isSecure(): bool
    {
        return key_exists('HTTPS', $_SERVER);
    }

    /**
     * Returns user agent from request.
     *
     * @return string
     */
    public function userAgent(): string
    {
        return $_SERVER['HTTP_USER_AGENT'];
    }

    /**
     * Returns user agent from request.
     *
     * @return string
     */
    public function ip(): string
    {
        return $_SERVER['REMOTE_ADDR'];
    }

    /**
     * Returns the method with which the request was made.
     *
     * @return string
     */
    public function method(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Returns requested url as is.
     *
     * @return string
     */
    public function fullurl(): string
    {
        return $_SERVER['REQUEST_URI'];
    }

    /**
     * Returns requested url string without GET parameters.
     *
     * @return string
     */
    public function url(): string
    {
        return explode('?', $this->fullurl(), 2)[0];
    }

    /**
     * Returns all request data that has been sent.
     *
     * @return array<string, mixed>
     */
    public function all(): array
    {
        return $_REQUEST;
    }

    /**
     * Returns value from request data specified by key.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return key_exists($key, $_REQUEST) ? $_REQUEST[$key] : $default;
    }
}
