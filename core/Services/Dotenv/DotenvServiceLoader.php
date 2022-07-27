<?php

namespace Uwi\Services\Dotenv;

use Uwi\Contracts\DotenvContract;
use Uwi\Services\ServiceLoader;

class DotenvServiceLoader extends ServiceLoader
{
    /**
     * Register necessary components for Serivce.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(DotenvContract::class, Dotenv::class);
        $this->app->singleton(DotenvContract::class)->loadEnvars();
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
