<?php

namespace Uwi\Foundation;

class Container
{
    /**
     * Container's bindings
     *
     * @var array
     */
    protected array $bindings = [];

    /**
     * Container's singletons
     *
     * @var array
     */
    protected array $singletons = [];

    /**
     * Bind abstract to concrete class
     *
     * @param string $abstract
     * @param string|null $concrete
     * @return static
     */
    public function bind(string $abstract, ?string $concrete = null): static
    {
        if ($concrete === null) {
            $concrete = $abstract;
        }

        $this->bindings[$abstract] = $concrete;

        return $this;
    }

    /**
     * Instantiate class
     *
     * @param string $abstract
     * @param mixed ...$args
     * @return object
     */
    public function instantiate(string $abstract, mixed ...$args): object
    {
        $concrete = $abstract;

        if (key_exists($abstract, $this->bindings)) {
            $concrete = $this->bindings[$abstract];
        }

        return new $concrete(...$args);
    }

    /**
     * Create a singleton
     *
     * @param string $abstract
     * @param mixed ...$args
     * @return object
     */
    public function singleton(string $abstract, mixed ...$args): object
    {
        $concrete = $abstract;

        if (key_exists($abstract, $this->bindings)) {
            $concrete = $this->bindings[$abstract];
        }

        if (!key_exists($abstract, $this->singletons)) {
            $this->singletons[$abstract] = $this->instantiate($concrete);

            $this->singletons[$abstract]->boot(...$args);
        }

        return $this->singletons[$abstract];
    }
}
