<?php

use Uwi\Dotenv\Dotenv;
use Uwi\Foundation\Application;

/**
 * Get the App instance
 *
 * @return Application
 */
function app(): Application
{
    return Application::$instance;
}

/**
 * Returns an envar
 *
 * @param string $key
 * @param string|null $default
 * @return string|null
 */
function env(string $key, ?string $default = null): ?string
{
    return app()->singleton(Dotenv::class)->get($key, $default);
}

/**
 * Get loaded config by specified key
 *
 * @param string $key
 * @param mixed $default
 * @return mixed
 * 
 * @throws NotFoundException
 */
function config(string $key, mixed $default = null): mixed
{
    return app()->getConfig($key, $default);
}

/**
 * Calls function or method with parameters injecting
 *
 * @param Closure|array $action Closure or array [object|string class, string method]
 * @return mixed
 */
function tap(Closure|array $action): mixed
{
    if (is_array($action)) {
        $object = $action[0];
        $method = $action[1];

        if (gettype($action[0]) === 'string') {
            $object = app()->instantiate($object);
        }

        return $object->{$method}(
            ...app()->resolveMethodParameter($object::class, $method)
        );
    } else if ($action instanceof Closure) {
        return $action(
            ...app()->resolveClosureParameter($action)
        );
    }
}
