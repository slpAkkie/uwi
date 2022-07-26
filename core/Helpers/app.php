<?php

use Uwi\Contracts\ApplicationContract;
use Uwi\Contracts\DotenvContract;

/**
 * Returns global Application instance.
 *
 * @return ApplicationContract
 */
function app(): ApplicationContract
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
    return app()->singleton(DotenvContract::class)->get($key, $default);
}
