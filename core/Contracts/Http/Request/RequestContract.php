<?php

namespace Uwi\Contracts\Http\Request;

use Uwi\Contracts\Container\SingletonContract;

interface RequestContract extends SingletonContract
{
    /**
     * Returns all cookie from the request or specified by key.
     *
     * @param string|null $key
     * @param string|null|null $default
     * @return array<string, string>|string
     */
    public function cookie(string|null $key = null, string|null $default = null): array|string;


    /**
     * Returns entry of Accept HTTP Header.
     *
     * @return string
     */
    public function accept(): string;


    /**
     * Returns HTTP host.
     *
     * @return string
     */
    public function host(): string;


    /**
     * Returns true if connection is under HTTPS protocol.
     *
     * @return boolean
     */
    public function isSecure(): bool;


    /**
     * Returns user agent from request.
     *
     * @return string
     */
    public function userAgent(): string;


    /**
     * Returns user agent from request.
     *
     * @return string
     */
    public function ip(): string;


    /**
     * Returns the method with which the request was made.
     *
     * @return string
     */
    public function method(): string;


    /**
     * Returns requested uri as is.
     *
     * @return string
     */
    public function fullUri(): string;


    /**
     * Returns requested uri string without GET parameters.
     *
     * @return string
     */
    public function uri(): string;


    /**
     * Returns all request data that has been sent.
     *
     * @return array<string, mixed>
     */
    public function all(): array;


    /**
     * Returns value from request data specified by key.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed;
}
