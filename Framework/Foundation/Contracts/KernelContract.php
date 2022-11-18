<?php

namespace Framework\Foundation\Contracts;

use Services\Container\Contracts\SingletonContract;

interface KernelContract extends SingletonContract
{
    /**
     * TODO: Undocumented function
     *
     * @param \Framework\Foundation\Contracts\ApplicationContract $app
     * @return void
     */
    public function start(ApplicationContract $app): void;
}
