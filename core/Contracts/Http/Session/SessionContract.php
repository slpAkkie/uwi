<?php

namespace Uwi\Contracts\Http\Session;

use Uwi\Contracts\Container\SingletonContract;

interface SessionContract extends SingletonContract
{
    /**
     * Set many values.
     *
     * @param array<string, string> $vars
     * @return \Uwi\Contracts\Http\Session\SessionContract
     */
    public function setMany(array $vars = []): \Uwi\Contracts\Http\Session\SessionContract;

    /**
     * Set single value.
     *
     * @param string $key
     * @param string $val
     * @return string
     */
    public function set(string $key, string $val): string;

    /**
     * Unset value by key.
     *
     * @param string $key
     * @return void
     */
    public function unset(string $key): void;

    /**
     * Clear all session values.
     *
     * @return \Uwi\Contracts\Http\Session\SessionContract
     */
    public function clear(): \Uwi\Contracts\Http\Session\SessionContract;

    /**
     * Get single value.
     *
     * @param string $key
     * @param string|null $default
     * @return string|null
     */
    public function get(string $key, string $default = null): string|null;

    /**
     * Get all session data.
     *
     * @return array
     */
    public function all(): array;

    /**
     * Start session.
     *
     * @return \Uwi\Contracts\Http\Session\SessionContract
     */
    public function start(): \Uwi\Contracts\Http\Session\SessionContract;

    /**
     * Write data to session and close.
     *
     * @return \Uwi\Contracts\Http\Session\SessionContract
     */
    public function close(): \Uwi\Contracts\Http\Session\SessionContract;

    /**
     * Destory session.
     *
     * @return \Uwi\Contracts\Http\Session\SessionContract
     */
    public function destory(): \Uwi\Contracts\Http\Session\SessionContract;

    /**
     * Destroy and start new session.
     *
     * @return \Uwi\Contracts\Http\Session\SessionContract
     */
    public function regenerate(): \Uwi\Contracts\Http\Session\SessionContract;
}
