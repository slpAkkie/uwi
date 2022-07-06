<?php

use Uwi\Foundation\Application;

/**
 * Get the App instance
 *
 * @return Application
 */
function app()
{
    return Application::$instance;
}

/**
 * Get loaded config by specified configuration name
 *
 * @param string $configurationName
 * @param ?string $key
 * @param mixed $default
 * @throws FileNotFoundException
 * @return mixed
 */
function config(string $configurationName, ?string $key = null, mixed $default = null): mixed
{
    return app()->getConfig($configurationName, $key, $default);
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
