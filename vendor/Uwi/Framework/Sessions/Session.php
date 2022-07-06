<?php

namespace Uwi\Sessions;

use Exception;

class Session
{
    /** 
     * Uwi session
     * 
     * @var Session
     */
    private $session;

    /**
     * Indicate if class has instantiated
     *
     * @var boolean
     */
    public static bool $isInstantiated = false;

    /**
     * Indicate if class has booted
     *
     * @var boolean
     */
    public static bool $isBooted = false;

    /**
     * Initialize the session
     *
     * @param array $params
     */
    public function __construct()
    {
        session_start();
        
        // Indicate that class has been instantiated
        self::$isInstantiated = true;
    }

    public function boot(): self {
        
        // Exit if class has been booted already
        if (self::$isBooted) return $this;

        // Indicate that class has been booted
        self::$isBooted = true;

        return $this;
    }

    /**
     * Store multiply values
     *
     * @param array $params
     * @return void
     */
    public function store(array $params = []): void {
        foreach($params as $k => $v) {
            $_SESSION[$k] = $v;
        }
    }

    /**
     * Set single value
     *
     * @param string|int $k
     * @param mixed $v
     * @return void
     */
    public function set(string $k, $v): void {
        $_SESSION[$k] = $v;
    }

    /**
     * Get single value
     *
     * @param string $param
     * @param mixed $default
     * @return mixed
     */
    public function get(string $param, mixed $default = null): mixed {
        return empty($_SESSION[$param])?$default:$_SESSION[$param];
    }

    /**
     * Clear session
     *
     * @return void
     */
    public function destroy(): void {
        session_destroy();
        session_start();
    }
}
