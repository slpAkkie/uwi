<?php

namespace Uwi\Support\Arrays;

use ArrayAccess;

abstract class ArrayWrapper implements ArrayAccess
{
    /**
     * Array storage
     *
     * @var array
     */
    protected array $store;

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

    /**
     * Convert collection to array
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->store;
    }
}
