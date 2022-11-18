<?php

namespace Framework\Foundation\Kernel;

use Framework\Foundation\Contracts\ApplicationContract;
use Framework\Foundation\Contracts\KernelContract;
use Services\Http\Contracts\Sessions\SessionContract;
use Services\Http\Contracts\Routing\RouterContract;

class WebKernel implements KernelContract
{
    /**
     * TODO: Undocumented function
     *
     * @param \Framework\Foundation\Contracts\ApplicationContract $app
     * @return void
     */
    public function start(ApplicationContract $app): void
    {
        app()->get(SessionContract::class)->start();

        $route = app()->get(RouterContract::class)->getRouteForRequest();
        if (!is_null($route)) {
            app()->tap($route->getHandler());
        }
    }
}
