<?php

namespace Uwi\Foundation\Providers;

use Uwi\Contracts\Http\Response\ResponseContract;
use Uwi\Contracts\Sessions\SessionContract;
use Uwi\Foundation\Http\Response\Response;
use Uwi\Foundation\Providers\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Array of bindings
     *
     * @var array
     */
    public array $bindings = [
        ResponseContract::class => Response::class,
    ];

    /**
     * Boot server provider
     * Calls when all other service provider are registered
     *
     * @return void
     */
    public function boot(): void
    {
        app()->singleton(SessionContract::class);
    }
}
