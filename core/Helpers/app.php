<?php

/**
 * Returns global Application instance.
 *
 * @return \Uwi\Contracts\Application\ApplicationContract
 */
function app(): \Uwi\Contracts\Application\ApplicationContract
{
    global $app;
    return $app;
}

/**
 * Returns an envar by key or default.
 *
 * @param string $key
 * @param string|null $default
 * @return string|null
 */
function env(string $key, string|null $default = null): string|null
{
    return app()->singleton(\Uwi\Services\Dotenv\Contracts\DotenvContract::class)->get($key, $default);
}

/**
 * Tap the function or class method.
 * Runs it and inject params.
 *
 * @param \Closure|string|array $action
 * @param mixed ...$args
 * @return mixed
 */
function tap(\Closure|string|array $action, mixed ...$args): mixed
{
    return app()->tap($action, ...$args);
}
