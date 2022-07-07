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
