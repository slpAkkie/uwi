<?php

namespace Uwi\Contracts\Application;

use Uwi\Contracts\Container\ContainerContract;

interface ApplicationContract extends ContainerContract
{
    /**
     * Instantiate Application.
     *
     * @param array<ServiceLoaderContract> $serviceLoaders
     * @return \Uwi\Contracts\Application\ApplicationContract
     */
    public static function create(array $serviceLoaders = []): \Uwi\Contracts\Application\ApplicationContract;

    /**
     * Register provided ServiceLoaders.
     *
     * @param array<ServiceLoaderContract> $serviceLoaders
     * @return void
     */
    public function registerServices(array $serviceLoaders = []): void;

    /**
     * Register provided Service by it's ServiceLoader.
     *
     * @param string $loader - Implementation of ServiceLoaderContract.
     * @return void
     */
    public function registerService(string $loader): void;

    /**
     * Tap the static class method.
     * Runs it and inject params.
     *
     * @param array $action
     * @param mixed ...$args
     * @return mixed
     */
    public function tapStatic(array $action, mixed ...$args): mixed;

    /**
     * Tap the function or class method.
     * Runs it and inject params.
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
