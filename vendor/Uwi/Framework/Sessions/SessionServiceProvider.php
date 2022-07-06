<?php

namespace Uwi\Sessions;

use Uwi\Contracts\Sessions\SessionContract;
use Uwi\Foundation\Providers\ServiceProvider;

class SessionServiceProvider extends ServiceProvider
{
    /**
     * Array of bindings
     *
     * @var array
     */
    public array $bindings = [
        SessionContract::class => Session::class,
    ];
}
