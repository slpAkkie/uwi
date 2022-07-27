<?php

namespace Uwi\Services\Http;

use Uwi\Contracts\Application\KernelContract;
use Uwi\Foundation\Kernel\HttpKernel;
use Uwi\Services\Http\Request\RequestServiceLoader;
use Uwi\Services\Http\Response\ResponseServiceLoader;
use Uwi\Services\Http\Routing\RoutingServiceLoader;
use Uwi\Services\Http\Session\SessionServiceLoader;
use Uwi\Services\ServiceLoader;

class HttpKernelServiceLoader extends ServiceLoader
{
    /**
     * Register necessary components for Serivce.
     *
     * @return void
     */
    public function register(): void
    {
        // Register Kernel dependencies.
        $this->app->registerServices([
            RoutingServiceLoader::class,
            RequestServiceLoader::class,
            SessionServiceLoader::class,
            ResponseServiceLoader::class,
        ]);

        // Register kernel.
        $this->app->bind(KernelContract::class, HttpKernel::class);
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