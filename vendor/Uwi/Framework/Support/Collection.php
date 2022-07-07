<?php

namespace Uwi\Support;

use Closure;

class Collection
{
    /**
     * Collection items
     *
     * @var array
     */
    private array $items = [];

    /**
     * Make new Collection from value
     *
     * @param mixed $val
     * @return static
     */
    public static function make(mixed $val): static
    {
        $instance = new static();

        if (is_array($val)) {
            $instance->items = $val;
        } else {
            $instance->items[] = $val;
        }

        return $instance;
    }

    /**
     * Apply callback on each item in collection and return new collection
     *
     * @param Closure $callback
     * @return static
     */
    public function map(Closure $callback): static
    {
        return static::make(array_map($callback, $this->items));
    }
}
