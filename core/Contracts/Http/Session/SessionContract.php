<?php

namespace Uwi\Contracts\Http\Session;

use Uwi\Contracts\Container\SingletonContract;

interface SessionContract extends SingletonContract
{
    /**
     * Set many values.
     *
     * @param array<string, string> $vars
     * @return static
     */
    public function setMany(array $vars = []): static;

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
     * @return static
     */
    public function clear(): static;

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
     * @return static
     */
    public function start(): static;

    /**
     * Write data to session and close.
     *
     * @return static
     */
    public function close(): static;

    /**
     * Destory session.
     *
     * @return static
     */
    public function destory(): static;

    /**
     * Destroy and start new session.
     *
     * @return static
     */
    public function regenerate(): static;
}
