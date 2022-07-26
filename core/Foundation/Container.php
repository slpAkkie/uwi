<?php

namespace Uwi\Foundation;

use Uwi\Contracts\ContainerContract;
use Uwi\Contracts\SingletonContract;

class Container implements ContainerContract
{
    /**
     * Container's bindings abstracts to concrete implementations.
     *
     * @var array<string, array<mixed>>
     */
    protected array $bindings = [];

    /**
     * Array of shared objects. They may be injected when it needed.
     *
     * @var array<string, object>
     */
    protected array $shared = [];

    /**
     * Array of instantiated singletons.
     *
     * @var array<string, object>
     */
    protected array $singletons = [];

    /**
     * Bindings abstract to concrete implementation.
     *
     * @param string $abstract
     * @param string $concrete
     * @param boolean $shared
     * @return void
     */
    public function bind(string $abstract, string $concrete, bool $shared = false): void
    {
        $this->bindings[$abstract] = [$concrete ?? $abstract, $shared];
    }

    /**
     * Returns true if abstract has been binded to the Container.
     *
     * @param string $abstract
     * @return boolean
     */
    protected function isBinded(string $abstract): bool
    {
        return key_exists($abstract, $this->bindings);
    }

    /**
     * Returns binded for the abstract or null.
     *
     * @param string $abstract
     * @return array<string, array<mixed>>|null
     */
    protected function getBinded(string $abstract): array|null
    {
        return $this->isBinded($abstract)
            ? $this->bindings[$abstract]
            : null;
    }

    /**
     * Create new instance of concrete for the abstract.
     * If abstract marked as shared then created instance
     * will be added for sharing into the Container.
     *
     * @param string $abstract
     * @param mixed ...$args
     * @return object
     */
    public function make(string $abstract, mixed ...$args): object
    {
        // Try to find binded for the abstract or set abstract itself.
        $concrete = $this->getBinded($abstract) ?? [
            $abstract,
            false,
        ];

        // If concrete isn't instantiable throw an exception.
        if ((class_exists($concrete[0]) && (new \ReflectionClass($concrete[0]))->isInstantiable()) === false) {
            throw new \Exception("Class [$concrete[0]] isn't instantiable");
        }

        // Create new instance of concrete for the abstract.
        $instance = method_exists($concrete[0], '__construct')
            ? new $concrete[0](...$this->resolveArgs([$concrete[0], '__construct'], $args))
            : new $concrete[0]();

        // Check if abstract should be shared.
        if ($concrete[1] === true) {
            $this->share($abstract, $instance);
        }

        // Return created instance.
        return $instance;
    }

    /**
     * Resolve parameters for \Callable function or class method.
     * Collects array of args to call this.
     *
     * @param \Closure|array $action
     * @param array<mixed> $passedArgs
     * @return array<mixed>
     */
    protected function resolveArgs(\Closure|array $action, array $passedArgs): array
    {
        // Get a list of parameters of a function or
        // method of a class that should be injected into it
        // using reflection classes.
        $argsToResolve = [];

        if (is_array($action)) {
            $argsToResolve = (new \ReflectionMethod($action[0], $action[1]))->getParameters();
        } else if ($action instanceof \Closure) {
            $argsToResolve = (new \ReflectionFunction($action))->getParameters();
        }

        // Collect the necessary arguments by checking them
        // in the list of bindings and in the singleton list,
        // which will indicate that there is no need to create
        // a new instance of this object.
        $args = [];

        foreach ($argsToResolve as $type) {
            $type = $type->getType()->getName();

            // If searching class is already instantiated and shared
            // get it and add to the args for \Callable.
            if ($this->isShared($type)) {
                $args[] = $this->getShared($type);
            }
            // Else check if it is a singleton class
            // and if so put it into the args.
            else if ($this->isSingleton($type)) {
                $args[] = $this->singleton($type);
            }
            // Else if it is a class then instantiate it and put.
            else if (\class_exists($type, false)) {
                $args[] = $this->make($type);
            }
            // Else if it not in the container and not a class
            // that can be instantiate then take arg from
            // passed parameters
            else {
                $args[] = array_shift($passedArgs);
            }
        }

        // Return list of collected args.
        return $args;
    }

    /**
     * Share instance into the Container linked to its abstract and itself.
     *
     * @param string $abstract
     * @param object $concrete
     * @return void
     */
    public function share(string $abstract, object $concrete): void
    {
        $this->shared[$abstract] = $concrete ?? $abstract;

        if ($concrete !== $abstract) {
            $this->shared[$concrete::class] = $concrete;
        }
    }

    /**
     * Returns true if abstract has been shared into the Container.
     *
     * @param string $abstract
     * @return boolean
     */
    protected function isShared(string $abstract): bool
    {
        return key_exists($abstract, $this->shared);
    }

    /**
     * Returns shared for the abstract or null.
     *
     * @param string $abstract
     * @return object|null
     */
    protected function getShared(string $abstract): object|null
    {
        return $this->isShared($abstract)
            ? $this->shared[$abstract]
            : null;
    }

    /**
     * Returns a singleton instance of abstract.
     * Creates new concrete class if there is no instance
     * for the abstract.
     *
     * @param string $abstract
     * @param mixed ...$args
     * @return object
     */
    public function singleton(string $abstract, mixed ...$args): object
    {
        // Try to find binded for the abstract
        // or set abstract as a concrete.
        $concrete = $this->getBinded($abstract) ?? [
            $abstract,
            false,
        ];

        // Create a new singleton instance if it hasn't already been done.
        if ($this->isSingletonInstantiated($abstract) === false) {
            $this->singletons[$abstract] = $this->make($concrete[0], ...$args);
        }

        // Return found singleton instance.
        return $this->singletons[$abstract];
    }

    /**
     * Returns true if the abstract implements a singleton contract.
     *
     * @param string $abstract
     * @return boolean
     */
    protected function isSingleton(string $abstract): bool
    {
        return class_exists($abstract, false)
            ? is_subclass_of($abstract, SingletonContract::class)
            : false;
    }

    /**
     * Returns true if singleton has been instantiated.
     *
     * @param string $abstract
     * @return boolean
     */
    protected function isSingletonInstantiated(string $abstract): bool
    {
        return key_exists($abstract, $this->singletons);
    }
}
