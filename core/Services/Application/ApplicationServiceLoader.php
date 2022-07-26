<?php

namespace Uwi\Services\Application;

use Uwi\Contracts\Application\ApplicationContract;
use Uwi\Services\ServiceLoader;

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
