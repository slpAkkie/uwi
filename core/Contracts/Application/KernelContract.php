<?php

namespace Uwi\Contracts\Application;

use Uwi\Contracts\Container\SingletonContract;
use Uwi\Contracts\Http\Response\ResponseContract;

interface KernelContract extends SingletonContract
{
    /**
     * Starts the kernel and pass control to it.
     *
     * @return ResponseContract
     */
    public function start(): ResponseContract;
}
