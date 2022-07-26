<?php

namespace Uwi\Foundation;

use Uwi\Container\Container;
use Uwi\Contracts\ApplicationContract;
use Uwi\Contracts\ServiceLoaderContract;

class Application extends Container implements ApplicationContract
{
    /**
     * List of registered service loaders
     *
     * @var array<string>
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

        foreach ($serviceLoaders as $loader) {
            $app->registerService($loader);
        }

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
     * Register provided Service by it's ServiceLoader.
     * Runs register method.
     *
     * @param string $loader - Implementation of ServiceLoaderContract
     * @return void
     */
    public function registerService(string $loader): void
    {
        $this->tap([$loader, 'register']);
        $this->registeredServiceLoaders[] = $loader;
    }

    /**
     * Tap the function or class method.
     * Runs it and inject params
     *
     * @param \Closure|string|array $action
     * @param mixed ...$args
     * @return mixed
     */
    public function tap(\Closure|string|array $action, mixed ...$args): mixed
    {
        if (is_array($action) && is_string($action[0])) {
            $action[0] = $this->resolve($action[0]);
        }

        if (is_array($action)) {
            return $action[0]->{$action[1]}(
                ...$this->resolveArgs([$action[0]::class, $action[1]], ...$args)
            );
        }

        return $action(
            ...$this->resolveArgs($action, ...$args)
        );
    }
}
