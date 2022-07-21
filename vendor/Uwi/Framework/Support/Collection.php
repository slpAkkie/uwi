<?php

namespace Uwi\Support;

use Closure;
use Uwi\Support\Arrays\ArrayWrapper;

class Collection extends ArrayWrapper
{
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
            $instance->store = $val;
        } else {
            $instance->store[] = $val;
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
        return static::make(array_map($callback, $this->store));
    }

    /**
     * Get first item from collection
     *
     * @return mixed
     */
    public function first(): mixed
    {
        return key_exists(0, $this->store) ? $this->store[0] : null;
    }
}
