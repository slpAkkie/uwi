<?php

namespace Uwi\Foundation\Kernel;

use Uwi\Contracts\Application\ApplicationContract;
use Uwi\Contracts\Application\KernelContract;
use Uwi\Contracts\Http\Request\RequestContract;
use Uwi\Contracts\Http\Routing\RouterContract;

class HttpKernel implements KernelContract
{
    public function __construct(
        protected ApplicationContract $app,
        protected RouterContract $router,
        protected RequestContract $request,
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
        $this->app->tap($this->router->current()->action());
    }
}
