<?php

namespace Uwi\Contracts\Application;

use Uwi\Contracts\Container\SingletonContract;

interface KernelContract extends SingletonContract
{
    /**
     * Starts the kernel and pass control to it.
     *
     * @return void
     */
    public function start(): void;
}
