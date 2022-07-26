<?php

namespace Uwi\Services\Http\Routing;

use Uwi\Contracts\Application\ApplicationContract;
use Uwi\Contracts\Http\Routing\RouteContract;
use Uwi\Contracts\Http\Routing\RouterContract;
use Uwi\Services\ServiceLoader;

class RoutingServiceLoader extends ServiceLoader
{
    /**
     * Register necessary components for Serive.
     *
     * @param ApplicationContract $app
     * @return void
     */
    public function register(ApplicationContract $app): void
    {
        $app->bind(RouterContract::class, Router::class, true);
        $app->bind(RouteContract::class, Route::class);
    }

    /**
     * Runs when all ServiceLoader has been registered.
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }
}
