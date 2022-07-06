<?php

namespace Uwi\Contracts;

interface SingletonContract
{
    /**
     * Calls when singleton has been instantiated and saved
     *
     * @return void
     */
    public function boot(): void;
}
