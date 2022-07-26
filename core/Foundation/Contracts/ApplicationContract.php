<?php

namespace Uwi\Foundation\Contracts;

use Uwi\Container\Contracts\ContainerContract;

interface ApplicationContract extends ContainerContract
{
    /**
     * Instantiate new Application instance.
     *
     * @return static
     */
    public static function create(): static;
}
