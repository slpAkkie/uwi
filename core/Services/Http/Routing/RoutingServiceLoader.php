<?php

namespace Uwi\Services\Http\Routing;

use Uwi\Contracts\Http\Routing\RouteContract;
use Uwi\Contracts\Http\Routing\RouterContract;
use Uwi\Services\ServiceLoader;

class RoutingServiceLoader extends ServiceLoader
{
    /**
     * Default folder with routes files.
     */
    protected const ROUTES_FOLDER = 'routes';

    /**
     * Files with routes
     */
    protected const ROUTES_FILES = [
        'web.php',
    ];

    /**
     * Register necessary components for Serive.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(RouterContract::class, Router::class, true);
        $this->app->bind(RouteContract::class, Route::class);
    }

    /**
     * Runs when all ServiceLoader has been registered.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->app->singleton(RouterContract::class);

        foreach (static::ROUTES_FILES as $routesFile) {
            include_once APP_BASE_PATH . DIRECTORY_SEPARATOR . static::ROUTES_FOLDER . DIRECTORY_SEPARATOR . $routesFile;
        }
    }
}
