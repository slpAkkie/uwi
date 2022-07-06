<?php

namespace Uwi\Contracts\Sessions;

use Uwi\Contracts\SingletonContract;

interface SessionContract extends SingletonContract
{
    /**
     * Set many values
     *
     * @param array $vars
     * @return static
     */
    public function setMany(array $vars = []): static;

    /**
     * Set single value
     *
     * @param string|int $key
     * @param mixed $val
     * @return mixed
     */
    public function set(string $key, mixed $val): mixed;

    /**
     * Get single value
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed;

    /**
     * Start session
     *
     * @return static
     */
    public function start(): static;

    /**
     * Destory session
     *
     * @return static
     */
    public function destory(): static;

    /**
     * Destroy and start new session
     *
     * @return static
     */
    public function regenerate(): static;
}
