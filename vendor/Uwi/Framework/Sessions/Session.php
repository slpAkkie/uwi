<?php

namespace Uwi\Sessions;

use Uwi\Contracts\Sessions\SessionContract;

class Session implements SessionContract
{
    /**
     * Calls when singleton has been instantiated and saved
     *
     * @return void
     */
    public function boot(): void
    {
        $this->start();
    }

    /**
     * Set many values
     *
     * @param array $vars
     * @return static
     */
    public function setMany(array $vars = []): static
    {
        $_SESSION = array_merge($_SESSION, $vars);

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
        return $_SESSION[$key] = $val;
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
        return key_exists($key, $_SESSION) ? $_SESSION[$key] : $default;
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
}
