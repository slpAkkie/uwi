<?php

namespace Uwi\Services\Http;

use Uwi\Contracts\Application\ApplicationContract;
use Uwi\Contracts\Application\KernelContract;
use Uwi\Foundation\Kernel\HttpKernel;
use Uwi\Services\Http\Request\RequestServiceLoader;
use Uwi\Services\Http\Routing\RoutingServiceLoader;
use Uwi\Services\ServiceLoader;

class HttpKernelServiceLoader extends ServiceLoader
{
    /**
     * Register necessary components for Serive.
     *
     * @param ApplicationContract $app
     * @return void
     */
    public function register(ApplicationContract $app): void
    {
        // Register Kernel dependencies.
        $app->registerServices([
            RoutingServiceLoader::class,
            RequestServiceLoader::class,
        ]);

        // Register kernel.
        $app->bind(KernelContract::class, HttpKernel::class);
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
