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
     */
    public function make(string $abstract, mixed ...$args): object;

    /**
     * Share instance into the Container linked to its abstract and itself.
     *
     * @param string $abstract
     * @param object $concrete
     * @return void
     */
    public function share(string $abstract, object $concrete): void;

    /**
     * Returns a singleton instance of abstract.
     * Creates new concrete class if there is no instance
     * for the abstract.
     *
     * @param string $abstract
     * @param array<mixed> ...$args
     * @return object
     */
    public function singleton(string $abstract, mixed ...$args): object;
}
