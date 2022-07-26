<?php

namespace Uwi\Contracts\Application;


interface ServiceLoaderContract
{
    /**
     * Register necessary components for Serive.
     *
     * @return void
     */
    public function register(): void;

    /**
     * Runs when all ServiceLoader has been registered.
     *
     * @return void
     */
    public function boot(): void;
}
