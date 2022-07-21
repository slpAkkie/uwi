<?php

namespace Uwi\Database\Lion;

use Uwi\Foundation\Providers\ServiceProvider;

class LionServiceProvider extends ServiceProvider
{
    /**
     * Register service provider
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * Boot server provider
     * Calls when all other service provider are registered
     *
     * @return void
     */
    public function boot(): void
    {
        app()->singleton(\Uwi\Database\Connection::class);
    }
}
