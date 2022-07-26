<?php

namespace Uwi\Contracts\Application;

use Uwi\Contracts\Container\ContainerContract;

interface ApplicationContract extends ContainerContract
{
    /**
     * Instantiate new Application instance.
     *
     * @param array<ServiceLoaderContract> $serviceLoaders
     * @return static
     */
    public static function create(array $serviceLoaders = []): static;

    /**
     * Register application into the Container
     *
     * @return void
     */
    public function registerApplication(): void;

    /**
     * Register provided Service by it's ServiceLoader.
     * Runs register method.
     *
     * @param string $loader - Implementation of ServiceLoaderContract
     * @return void
     */
    public function registerService(string $loader): void;

    /**
     * Tap the function or class method.
     * Runs it and inject params
     *
     * @param \Closure|string|array $action
     * @param mixed ...$args
     * @return mixed
     */
    public function tap(\Closure|string|array $action, mixed ...$args): mixed;

    /**
     * Launches the application.
     *
     * @return void
     */
    public function start(): void;
}
