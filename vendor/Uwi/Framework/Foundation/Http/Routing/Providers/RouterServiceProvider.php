<?php

namespace Uwi\Foundation\Http\Routing\Providers;

use Uwi\Contracts\Http\Routing\RouterContract;
use Uwi\Contracts\Http\Routing\RouteContract;
use Uwi\Foundation\Http\Routing\Route;
use Uwi\Foundation\Http\Routing\Router;
use Uwi\Foundation\Providers\ServiceProvider;

class RouterServiceProvider extends ServiceProvider
{
    /**
     * Array of bindings
     *
     * @var array
     */
    public array $bindings = [
        RouterContract::class => Router::class,
        RouteContract::class => Route::class,
    ];
}
