<?php

namespace Uwi\Services;

use Uwi\Contracts\ApplicationContract;
use Uwi\Contracts\ServiceLoaderContract;

abstract class ServiceLoader implements ServiceLoaderContract
{
    /**
     * Register necessary components for Serive.
     *
     * @param ApplicationContract $app
     * @return void
     */
    public function register(ApplicationContract $app): void
    {
        //
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