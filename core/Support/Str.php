<?php

namespace Uwi\Support;

class Str
{
    /**
     * Convert string to plural
     * TODO: Improve
     *
     * @param string $singular
     * @return string
     */
    public static function plural(string $singular): string
    {
        return $singular . 's';
    }

    /**
     * Make a string's first character uppercase
     *
     * @param string $str
     * @return string
     */
    public static function upperFirst(string $str): string
    {
        return ucfirst($str);
    }

    /**
     * Convert strong to lower case
     *
     * @param string $str
     * @return string
     */
    public static function lower(string $str): string
    {
        return strtolower($str);
    }
}
