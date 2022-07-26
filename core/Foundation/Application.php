<?php

namespace Uwi\Foundation;

use Uwi\Container\Container;
use Uwi\Foundation\Contracts\ApplicationContract;

class Application extends Container implements ApplicationContract
{
    /**
     * Instantiate Application and bind it to the Container
     */
    public function __construct()
    {
        $this->bind(ApplicationContract::class, static::class, true);
        $this->share(ApplicationContract::class, $this);
    }
}
