<?php

namespace Uwi\Contracts\Application;

use Uwi\Contracts\Application\ApplicationContract;

interface ServiceLoaderContract
{
    /**
     * Register necessary components for Serive.
     *
     * @param ApplicationContract $app
     * @return void
     */
    public function register(ApplicationContract $app): void;

    /**
     * Runs when all ServiceLoader has been registered.
     *
     * @return void
     */
    public function boot(): void;
}
