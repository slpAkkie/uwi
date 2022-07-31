<?php

namespace Uwi\Services\Http;

use Uwi\Contracts\Application\KernelContract;
use Uwi\Foundation\Kernel\HttpKernel;
use Uwi\Services\Calibri\CalibriServiceLoader;
use Uwi\Services\Calibri\Contracts\ViewContract;
use Uwi\Services\Database\Lion\LionServiceLoader;
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
        $this->app->registerServices([
            // Register Kernel dependencies.
            SessionServiceLoader::class,
            RequestServiceLoader::class,
            ResponseServiceLoader::class,
            RoutingServiceLoader::class,

            // Register Optional services.
            LionServiceLoader::class,
            CalibriServiceLoader::class,
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
        // Register views namespace for error pages.
        $this->app->concreteFor(ViewContract::class)::namespace('errors', '/core/Views/errors');
    }
}
