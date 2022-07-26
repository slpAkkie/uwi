<?php

namespace Uwi\Services\Dotenv;

use Uwi\Contracts\ApplicationContract;
use Uwi\Contracts\DotenvContract;
use Uwi\Services\ServiceLoader;

class DotenvServiceLoader extends ServiceLoader
{
    /**
     * Register necessary components for Serive.
     *
     * @return void
     */
    public function register(ApplicationContract $app): void
    {
        $app->bind(DotenvContract::class, Dotenv::class);
        $app->singleton(DotenvContract::class)->loadEnvars();
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
