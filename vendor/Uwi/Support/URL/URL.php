<?php

namespace Uwi\Support\URL;

class URL
{
    /**
     * Compare two uri strings
     *
     * @param string $first
     * @param string $second
     * @return boolean
     */
    public static function compare(string $first, string $second): bool
    {
        // TODO: Improve check
        return $first === $second;
    }
}
