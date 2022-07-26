<?php

namespace Uwi\Services\Http\Routing;

use Uwi\Contracts\Http\Routing\RouterContract;

class Route implements RouterContract
{
    public function __construct(
        protected RouterContract $router,
    ) {
        //
    }
}
