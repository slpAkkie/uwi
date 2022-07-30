<?php

namespace Uwi\Support;

class Str
{
    /**
     * Convert word to plural.
     * TODO: Improve converting...
     *
     * @param string $str
     * @return string
     */
    public static function plural(string $str): string
    {
        return $str . 's';
    }

    /**
     * Make a string's first character uppercase.
     *
     * @param string $str
     * @return string
     */
    public static function upperFirst(string $str): string
    {
        return ucfirst($str);
    }

    /**
     * Convert string to lower case.
     *
     * @param string $str
     * @return string
     */
    public static function lower(string $str): string
    {
        return strtolower($str);
    }
}
