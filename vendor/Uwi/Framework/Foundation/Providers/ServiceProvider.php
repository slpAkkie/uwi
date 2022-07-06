<?php

namespace Uwi\Foundation\Providers;

abstract class ServiceProvider
{
    /**
     * Array of bindings
     *
     * @var array
     */
    public array $bindings = [];

    /**
     * Indicate if service provider has been registered
     *
     * @var boolean
     */
    public bool $registered = false;

    /**
     * Indicate if service provider has been booted
     *
     * @var boolean
     */
    public bool $booted = false;

    /**
     * Register service provider
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * Boot server provider
     * Calls when all other service provider are registered
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }
}
