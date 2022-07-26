<?php

namespace Uwi\Foundation\Kernel;

use Uwi\Contracts\Application\ApplicationContract;
use Uwi\Contracts\Application\KernelContract;

class HttpKernel implements KernelContract
{
    public function __construct(
        public ApplicationContract $app
    ) {
        //
    }

    /**
     * Starts the kernel and pass control to it.
     *
     * @return void
     */
    public function start(): void
    {
        //
    }
}
