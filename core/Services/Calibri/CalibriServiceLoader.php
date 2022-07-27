<?php

namespace Uwi\Services\Calibri;

use Uwi\Services\Calibri\Contracts\CompilerContract;
use Uwi\Services\Calibri\Contracts\ViewContract;
use Uwi\Services\ServiceLoader;

class CalibriServiceLoader extends ServiceLoader
{
    /**
     * Register necessary components for Serivce.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(ViewContract::class, View::class);
        $this->app->bind(CompilerContract::class, Compiler::class);
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
