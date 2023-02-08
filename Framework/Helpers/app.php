<?php

use Framework\Foundation\Application;
use Framework\Foundation\Contracts\ApplicationContract;
use Services\Dotenv\Contracts\DotenvContract;

function app(): ApplicationContract
{
    return Application::getInstance();
}

function env(string $key, ?string $default = null): ?string
{
    return app()->get(DotenvContract::class)->get($key, $default);
}
