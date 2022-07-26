<?php

namespace Uwi\Services;

use Uwi\Contracts\Application\ApplicationContract;
use Uwi\Contracts\Application\ServiceLoaderContract;

abstract class ServiceLoader implements ServiceLoaderContract
{
    /**
     * Instantiate ServiceLoader
     *
     * @param ApplicationContract $app
     */
    public function __construct(
        protected ApplicationContract $app,
    ) {
        //
    }

    /**
     * Register necessary components for Serive.
     *
     * @return void
     */
    public function register(): void
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
