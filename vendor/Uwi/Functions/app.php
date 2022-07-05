<?php

/**
 * Get the App instance
 *
 * @return App
 */
function app()
{
    return Uwi\Core\App::$instance;
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
