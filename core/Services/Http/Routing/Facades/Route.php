<?php

namespace Uwi\Services\Http\Routing\Facades;

use Uwi\Services\Http\Routing\Factory\RouteFactory;
use Uwi\Support\Facades\Facade;

class Route extends Facade
{
    /**
     * Return a corresponding class or a Contract from Container bindings.
     *
     * @return string
     */
    public function getAccessor(): string
    {
        return RouteFactory::class;
    }
}
