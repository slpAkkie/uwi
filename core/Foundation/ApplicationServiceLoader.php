<?php

namespace Uwi\Foundation;

use Uwi\Contracts\Database\ConnectionContract;
use Uwi\Services\Database\Connection;
use Uwi\Services\Database\Lion\LionServiceLoader;
use Uwi\Services\Dotenv\DotenvServiceLoader;
use Uwi\Services\ServiceLoader;

class ApplicationServiceLoader extends ServiceLoader
{
    /**
     * Register necessary components for Serivce.
     *
     * @return void
     */
    public function register(): void
    {
        // Register Application dependencies.
        $this->app->registerServices([
            DotenvServiceLoader::class,
            LionServiceLoader::class,
        ]);

        // Register Conncetion.
        $this->app->bind(ConnectionContract::class, Connection::class);
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
