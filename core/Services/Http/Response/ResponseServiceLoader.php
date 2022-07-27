<?php

namespace Uwi\Services\Http\Response;

use Uwi\Contracts\Http\Response\ResponseContract;
use Uwi\Services\ServiceLoader;

class ResponseServiceLoader extends ServiceLoader
{
    /**
     * Register necessary components for Serivce.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(ResponseContract::class, Response::class);
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
