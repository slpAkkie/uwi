<?php

namespace Uwi\Support\Facades;

use Uwi\Contracts\Support\Facades\FacadeContract;

abstract class Facade implements FacadeContract
{
    /**
     * Create new instance of accesssor and call a method on it.
     *
     * @param string $name
     * @param array<mixed> $args
     * @return mixed
     */
    public static function __callStatic($name, $args): mixed
    {
        return app()->make((new static())->getAccessor())->{$name}(...$args);
    }
}
