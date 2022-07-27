<?php

namespace Uwi\Services\Http\Session;

use Uwi\Contracts\Http\Session\SessionContract;

class Session implements SessionContract
{
    /**
     * Deafult path where to store sessions.
     * 
     * @var string
     */
    protected const SESSION_SAVE_PATH = '/storage/framework/sessions';

    /**
     * Instantiate new Session Service.
     */
    public function __construct()
    {
        session_save_path(APP_BASE_PATH . static::SESSION_SAVE_PATH);
    }

    /**
     * Destruct Session Service.
     */
    public function __destruct()
    {
        $this->close();
    }

    /**
     * Set many values.
     *
     * @param array<string, string> $vars
     * @return static
     */
    public function setMany(array $vars = []): static
    {
        $_SESSION = array_merge($_SESSION, $vars);

        return $this;
    }

    /**
     * Set single value.
     *
     * @param string $key
     * @param string $val
     * @return string
     */
    public function set(string $key, string $val): string
    {
        $_SESSION[$key] = $val;

        return $val;
    }

    /**
     * Unset value by key.
     *
     * @param string $key
     * @return void
     */
    public function unset(string $key): void
    {
        if (key_exists($key, $_SESSION)) {
            unset($_SESSION[$key]);
        }
    }

    /**
     * Clear all session values.
     *
     * @return static
     */
    public function clear(): static
    {
        $_SESSION = [];

        return $this;
    }

    /**
     * Get single value.
     *
     * @param string $key
     * @param string|null $default
     * @return string|null
     */
    public function get(string $key, string $default = null): string|null
    {
        return key_exists($key, $_SESSION) ? $_SESSION[$key] : $default;
    }

    /**
     * Get all session data.
     *
     * @return array
     */
    public function all(): array
    {
        return $_SESSION;
    }

    /**
     * Start session.
     *
     * @return static
     */
    public function start(): static
    {
        session_start();

        return $this;
    }

    /**
     * Write data to session and close.
     *
     * @return static
     */
    public function close(): static
    {
        session_write_close();

        return $this;
    }

    /**
     * Destory session.
     *
     * @return static
     */
    public function destory(): static
    {
        session_destroy();
        $this->clear();

        return $this;
    }

    /**
     * Destroy and start new session.
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
