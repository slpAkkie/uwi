<?php

namespace Uwi\Services\Http\Request;

use Uwi\Contracts\Application\ApplicationContract;
use Uwi\Contracts\Http\Request\RequestContract;
use Uwi\Services\ServiceLoader;

class RequestServiceLoader extends ServiceLoader
{
    /**
     * Register necessary components for Serive.
     *
     * @param ApplicationContract $app
     * @return void
     */
    public function register(ApplicationContract $app): void
    {
        $app->bind(RequestContract::class, Request::class, true);
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
