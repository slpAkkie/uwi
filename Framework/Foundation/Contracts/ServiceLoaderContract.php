<?php

namespace Framework\Foundation\Contracts;

interface ServiceLoaderContract
{
    /**
     * TODO: Undocumented function
     *
     * @param \Framework\Foundation\Contracts\ApplicationContract $app
     * @return void
     */
    public function register(ApplicationContract $app): void;

    /**
     * TODO: Undocumented function
     *
     * @param \Framework\Foundation\Contracts\ApplicationContract $app
     * @return void
     */
    public function boot(ApplicationContract $app): void;
}
