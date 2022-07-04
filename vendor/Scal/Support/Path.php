<?php

/*
|
|--------------------------------------------------
| Class for work with paths
|--------------------------------------------------
|
| Support to work with paths.
| Useful only for Scal
|
*/

namespace Scal\Support;

class Path
{
    /**
     * Normalize path or array of paths
     *
     * @param string|array $path
     * @return string|array
     */
    public static function normalize($path)
    {
        switch (gettype($path)) {
            case 'string':
                return self::normalizePath($path);
            case 'array':
                foreach ($path as $i => $value) $path[$i] = self::normalize($value);
                return $path;
            default:
                return null;
        }
    }

    /**
     * Normalize path
     *
     * @param string $path
     * @return string
     */
    private static function normalizePath(string $path): string
    {
        $path = preg_replace(
            ['/[\/\\\]/', '/^\./'],
            [DIRECTORY_SEPARATOR, realpath('')],
            trim($path, '/\\')
        );

        if (PHP_OS === 'Linux') $path = DIRECTORY_SEPARATOR . $path;

        return $path;
    }

    /**
     * Parse path from \Scal\Loader::$NP
     * Whether it's a string or an array
     *
     * @param string|array $path
     * @return string|array|null
     *
     * @throws \Scal\Exceptions\WrongConfigurationException
     */
    public static function parse($path)
    {
        switch (gettype($path)) {
            case 'array':
                $path = array_map(function ($p) {
                    return Path::parsePath($p);
                }, $path);

                foreach ($path as $i => $el) {
                    if (gettype($el) === 'array') {
                        array_splice($path, $i, 1, $el);
                    }
                }
                return $path;
            case 'string':
                return self::parsePath($path);
            default:
                if (SCAL_EXCEPTION_MODE)
                    throw new \Scal\Exceptions\WrongConfigurationException();
                else return null;
        }
    }

    /**
     * Parse path from \Scal\Loader::$NP
     * Only string
     *
     * @param string $path
     * @return string|array
     */
    private static function parsePath(string $path)
    {
        if (substr($path, -1) === '*')
            return array_merge(
                [substr($path, 0, -2)],
                Path::subdirs($path) ?? []
            );
        else return Path::normalize($path);
    }

    /**
     * Recursively get all subdirectories
     *
     * @param string $root
     * @return array|null
     */
    public static function subdirs($root): ?array
    {
        $root_subs = glob($root, GLOB_ONLYDIR);

        if (count($root_subs) === 0) return null;

        foreach ($root_subs as $child) {
            array_push(
                $root_subs,
                ...(self::subdirs("{$child}\\*") ?? [])
            );
        }



        return $root_subs;
    }

    /**
     * Join parts of path to one string
     *
     * @param string ...$ Path parts
     * @return string
     */
    public static function join(...$parts): string
    {
        return join(DIRECTORY_SEPARATOR, self::normalize(array_filter($parts)));
    }
}
