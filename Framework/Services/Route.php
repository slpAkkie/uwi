<?php

namespace Services;

use Services\Container\Contracts\SingletonContract;
use Services\Http\Routing\Route as BaseRoute;

class Route extends BaseRoute implements SingletonContract
{
    /**
     * TODO: Undocumented function
     *
     * @return mixed
     */
    public function getHandler(): mixed
    {
        if (is_array($this->handler) && is_string($this->handler[0])) {
            $this->handler[0] = app()->new($this->handler[0]);
        }

        return $this->handler;
    }
}
