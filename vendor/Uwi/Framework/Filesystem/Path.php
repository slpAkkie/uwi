<?php

namespace Uwi\Filesystem;

class Path
{
    /**
     * Join path parts together using OS directory separator
     *
     * @param [type] ...$arguments
     * @return string
     */
    public static function glue(...$arguments): string
    {
        return join(DIR_SEP, $arguments);
    }
}
