<?php

namespace Uwi\Services;

use Uwi\Contracts\Application\ApplicationContract;
use Uwi\Contracts\Application\KernelContract;
use Uwi\Foundation\Kernel\HttpKernel;

class ApplicationServiceLoader extends ServiceLoader
{
    /**
     * Register necessary components for Serive.
     *
     * @param ApplicationContract $app
     * @return void
     */
    public function register(ApplicationContract $app): void
    {
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
