<?php

namespace Uwi\Services\Http\Session;

use Uwi\Contracts\Http\Session\SessionContract;

class Session implements SessionContract
{
    /**
     * Instantiate Session.
     */
    public function __construct(?string $save_path = null)
    {
        if (!is_null($save_path))
            session_save_path(APP_BASE_PATH . $save_path);
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
     * @return \Uwi\Contracts\Http\Session\SessionContract
     */
    public function setMany(array $vars = []): \Uwi\Contracts\Http\Session\SessionContract
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
     * @return \Uwi\Contracts\Http\Session\SessionContract
     */
    public function clear(): \Uwi\Contracts\Http\Session\SessionContract
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
     * @return \Uwi\Contracts\Http\Session\SessionContract
     */
    public function start(): \Uwi\Contracts\Http\Session\SessionContract
    {
        session_start();

        return $this;
    }

    /**
     * Write data to session and close.
     *
     * @return \Uwi\Contracts\Http\Session\SessionContract
     */
    public function close(): \Uwi\Contracts\Http\Session\SessionContract
    {
        session_write_close();

        return $this;
    }

    /**
     * Destory session.
     *
     * @return \Uwi\Contracts\Http\Session\SessionContract
     */
    public function destory(): \Uwi\Contracts\Http\Session\SessionContract
    {
        session_destroy();
        $this->clear();

        return $this;
    }

    /**
     * Destroy and start new session.
     *
     * @return \Uwi\Contracts\Http\Session\SessionContract
     */
    public function regenerate(): \Uwi\Contracts\Http\Session\SessionContract
    {
        $this->destory();
        $this->start();

        return $this;
    }
}
