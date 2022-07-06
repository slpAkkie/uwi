<?php

namespace Uwi\Sessions;

use ArrayAccess;
use Uwi\Contracts\Sessions\SessionContract;

class Session implements SessionContract, ArrayAccess
{
    /**
     * Session storage
     *
     * @var array
     */
    private array $store;

    /**
     * Calls when singleton has been instantiated and saved
     *
     * @return void
     */
    public function boot(): void
    {
        $this->start();
        $this->store = $_SESSION;
    }

    /**
     * Set many values
     *
     * @param array $vars
     * @return static
     */
    public function setMany(array $vars = []): static
    {
        $this->store = array_merge($this->store, $vars);

        return $this;
    }

    /**
     * Set single value
     *
     * @param string|int $key
     * @param mixed $val
     * @return mixed
     */
    public function set(string $key, mixed $val): mixed
    {
        return $this->store[$key] = $val;
    }

    /**
     * Get single value
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->has($key) ? $this->store[$key] : $default;
    }

    /**
     * Unset the value
     *
     * @param string $key
     * @return void
     */
    public function unset(string $key)
    {
        unset($this->store[$key]);
    }

    /**
     * Check wheter the key exists
     *
     * @param string $key
     * @return boolean
     */
    public function has(string $key): bool
    {
        return key_exists($key, $this->store);
    }

    /**
     * Start session
     *
     * @return static
     */
    public function start(): static
    {
        session_start();

        return $this;
    }

    /**
     * Destory session
     *
     * @return static
     */
    public function destory(): static
    {
        session_destroy();

        return $this;
    }

    /**
     * Destroy and start new session
     *
     * @return static
     */
    public function regenerate(): static
    {
        $this->destory();
        $this->start();

        return $this;
    }



    /**
     * Implementation of ArrayAccess
     *
     * @param int|string $key
     * @param mixed $value
     * @return void
     */
    public function offsetSet($key, $value): void
    {
        $this->set($key, $value);
    }

    /**
     * Implementation of ArrayAccess
     *
     * @param int|string $key
     * @return boolean
     */
    public function offsetExists($key): bool
    {
        return $this->has($key);
    }

    /**
     * Implementation of ArrayAccess
     *
     * @param int|string $key
     * @return void
     */
    public function offsetUnset($key): void
    {
        $this->unset($key);
    }

    /**
     * Implementation of ArrayAccess
     *
     * @param int|string $key
     * @return mixed
     */
    public function offsetGet($key): mixed
    {
        return $this->get($key);
    }
}
