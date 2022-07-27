<?php

namespace Uwi\Services\Http\Session;

use Uwi\Contracts\Http\Session\SessionContract;
use Uwi\Services\ServiceLoader;

class SessionServiceLoader extends ServiceLoader
{
    /**
     * Register necessary components for Serivce.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(SessionContract::class, Session::class, true);
    }

    /**
     * Runs when all ServiceLoader has been registered.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->app->resolve(SessionContract::class)->start();
    }
}
