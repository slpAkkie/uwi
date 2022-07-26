<?php

namespace Uwi\Foundation;

use Uwi\Container\Container;
use Uwi\Foundation\Contracts\ApplicationContract;

class Application extends Container implements ApplicationContract
{
    /**
     * Instantiate new Application instance.
     *
     * @return static
     */
    public static function create(): static
    {
        $app = new static();

        $app->bind(ApplicationContract::class, static::class, true);
        $app->share(ApplicationContract::class, $app);

        return $app;
    }
}
