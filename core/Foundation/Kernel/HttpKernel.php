<?php

namespace Uwi\Foundation\Kernel;

use Uwi\Contracts\Application\ApplicationContract;
use Uwi\Contracts\Application\KernelContract;
use Uwi\Contracts\Application\ResponseContract;
use Uwi\Contracts\Http\Request\RequestContract;
use Uwi\Contracts\Http\Routing\RouterContract;
use Uwi\Services\Http\Response\Facades\Response;

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
     * @return ResponseContract
     */
    public function start(): ResponseContract
    {
        $controllerResponse = $this->app->tap($this->router->current()->action()) ?? null;

        return Response::make($controllerResponse);
    }
}
