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
     * Register provided ServiceLoaders
     *
     * @param array<ServiceLoaderContract> $serviceLoaders
     * @return void
     */
    public function registerServices(array $serviceLoaders = []): void;

    /**
     * Register provided Service by it's ServiceLoader.
     *
     * @param string $loader - Implementation of ServiceLoaderContract
     * @return void
     */
    public function registerService(string $loader): void;

    /**
     * Boot registered ServiceLoaders
     *
     * @return void
     */
    public function bootServices(): void;

    /**
     * Boot ServiceLoader
     *
     * @param ServiceLoaderContract $loader
     * @return void
     */
    public function bootService(ServiceLoaderContract $loader): void;

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
