<?php

namespace Services;

use Framework\Foundation\Contracts\ApplicationContract;
use Framework\Foundation\Contracts\ServiceLoaderContract;
use Services\Dotenv\Contracts\DotenvContract;

class DotenvServiceLoader implements ServiceLoaderContract
{
    protected array $bindings = [
        DotenvContract::class => Dotenv::class,
    ];

    /**
     * TODO: Undocumented function
     *
     * @param \Framework\Foundation\Contracts\ApplicationContract $app
     * @return void
     */
    public function register(ApplicationContract $app): void
    {
        foreach ($this->bindings as $abstract => $concrete) {
            $app->bind($abstract, $concrete);
        }
    }

    /**
     * TODO: Undocumented function
     *
     * @param \Framework\Foundation\Contracts\ApplicationContract $app
     * @return void
     */
    public function boot(ApplicationContract $app): void
    {
        foreach (array_keys($this->bindings) as $abstract) {
            $app->share($app->new($abstract));
        }
    }
}
