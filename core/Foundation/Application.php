<?php

namespace Uwi\Foundation;

use Uwi\Container\Container;
use Uwi\Contracts\Application\ApplicationContract;
use Uwi\Contracts\Application\KernelContract;
use Uwi\Contracts\Application\ServiceLoaderContract;

class Application extends Container implements ApplicationContract
{
    /**
     * List of registered service loaders
     *
     * @var array<string, ServiceLoaderContract>
     */
    protected array $registeredServiceLoaders = [];

    /**
     * Instantiate new Application instance.
     *
     * @param array<ServiceLoaderContract> $serviceLoaders
     * @return static
     */
    public static function create(array $serviceLoaders = []): static
    {
        $app = new static();

        $app->registerApplication();
        $app->registerServices($serviceLoaders);

        return $app;
    }

    /**
     * Register application into the Container.
     *
     * @return void
     */
    public function registerApplication(): void
    {
        $this->bind(ApplicationContract::class, static::class, true);
        $this->share(ApplicationContract::class, $this);
    }

    /**
     * Register provided ServiceLoaders
     *
     * @param array<ServiceLoaderContract> $serviceLoaders
     * @return void
     */
    public function registerServices(array $serviceLoaders = []): void
    {
        foreach ($serviceLoaders as $loader) {
            $this->registerService($loader);
        }
    }

    /**
     * Register provided Service by it's ServiceLoader.
     *
     * @param string $loader - Implementation of ServiceLoaderContract
     * @return void
     */
    public function registerService(string $loader): void
    {
        $loader = $this->make($loader);
        $this->tap([$loader, 'register']);

        $this->registeredServiceLoaders[$loader::class] = $loader;
    }

    /**
     * Boot registered ServiceLoaders
     *
     * @return void
     */
    public function bootServices(): void
    {
        foreach ($this->registeredServiceLoaders as $loader) {
            $this->bootService($loader);
        }
    }

    /**
     * Boot ServiceLoader
     *
     * @param ServiceLoaderContract $loader
     * @return void
     */
    public function bootService(ServiceLoaderContract $loader): void
    {
        $loader->boot();
    }

    /**
     * Tap the function or class method.
     * Runs it and inject params.
     *
     * @param \Closure|string|array $action
     * @param mixed ...$args
     * @return mixed
     */
    public function tap(\Closure|string|array $action, mixed ...$args): mixed
    {
        // Resolve class if it's provided by class name.
        if (is_array($action) && is_string($action[0])) {
            $action[0] = $this->resolve(
                abstract: $action[0],
                shared: false
            );
        }

        // If action provided as array then call class method.
        if (is_array($action)) {
            return $action[0]->{$action[1]}(
                ...$this->resolveArgs([$action[0]::class, $action[1]], ...$args)
            );
        }

        // Otherwise, call it as a function.
        return $action(
            ...$this->resolveArgs($action, ...$args)
        );
    }

    /**
     * Launches the application.
     *
     * @return void
     */
    public function start(): void
    {
        $this->bootServices();
        $response = $this->tap([KernelContract::class, 'start']);

        dd('Application response:', $response);
    }
}
