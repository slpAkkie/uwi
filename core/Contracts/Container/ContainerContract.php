<?php

namespace Uwi\Contracts\Container;

interface ContainerContract
{
    /**
     * Bindings abstract to concrete implementation.
     *
     * @param string $abstract
     * @param string $concrete
     * @param boolean $shared
     * @return void
     */
    public function bind(string $abstract, string $concrete, bool $shared = false): void;

    /**
     * Create new instance of concrete for the abstract.
     * If abstract marked as shared then created instance
     * will be added for sharing into the Container.
     *
     * @param string $abstract
     * @param array<mixed> ...$args
     * @return object
     * 
     * @throws Exception
     */
    public function make(string $abstract, mixed ...$args): object;

    /**
     * Resolve parameters for \Callable function or class method.
     * Collects array of args to call this.
     *
     * @param \Closure|string|array $action
     * @param array<mixed> ...$passedArgs
     * @return array<mixed>
     */
    public function resolveArgs(\Closure|string|array $action, mixed ...$passedArgs): array;

    /**
     * Share instance into the Container linked to its abstract and itself.
     *
     * @param string|object $abstract
     * @param object|null $concrete
     * @return void
     */
    public function share(string|object $abstract, object|null $concrete = null): void;

    /**
     * Returns a singleton instance of abstract.
     * Creates new concrete class if there is no instance
     * for the abstract.
     *
     * @param string $abstract
     * @param array<mixed> ...$args
     * @return object
     * 
     * @throws Exception
     */
    public function singleton(string $abstract, mixed ...$args): object;

    /**
     * Resolve provided abstract.
     * Check wheter it is a Singletone, find in the container
     * or create new instance.
     *
     * @param string $abstract
     * @param array<mixed> $args
     * @param bool $shared
     * @return object|null
     */
    public function resolve(string $abstract, array $args = [], bool $shared = true): object|null;
}
