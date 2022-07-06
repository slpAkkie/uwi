<?php

namespace Uwi\Foundation\Providers;

use Uwi\Contracts\Sessions\SessionContract;
use Uwi\Foundation\Providers\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Boot server provider
     * Calls when all other service provider are registered
     *
     * @return void
     */
    public function boot(): void
    {
        app()->singleton(SessionContract::class);
    }
}
