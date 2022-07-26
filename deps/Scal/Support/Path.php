<?php

namespace Scal\Support;

class Path
{
    /**
     * Directories that should be skipped
     * 
     * @var array
     */
    private const DIRECTORIES_TO_SKIP = [
        '.', '..',
    ];

    /**
     * Get subdirectories
     *
     * @param string $path
     * @return array
     */
    public static function getSubdirectories(string $path): array
    {
        $subdirectories = [
            $path,
        ];

        foreach (scandir($path) as $entry) {
            if (in_array($entry, self::DIRECTORIES_TO_SKIP)) continue;
            $entry = self::glue($path, $entry);
            if (is_file($entry)) continue;

            if (is_dir($entry)) {
                $subdirectories = array_merge(self::getSubdirectories(Path::glue($entry)), $subdirectories);
            } else {
                $subdirectories[] = $entry;
            }
        }

        return $subdirectories;
    }

    /**
     * Unify the path
     *
     * @param string $path
     * @return string|array
     */
    public static function unify(string $path): string|array
    {
        $path = preg_replace('/\\\/', DIRECTORY_SEPARATOR, $path);

        if (str_ends_with($path, '*')) {
            $path = self::getSubdirectories(substr($path, 0, -2));
        }

        return $path;
    }

    /**
     * Parse array of paths and make it uniformly
     *
     * @param array $arrayOfPath
     * @return array
     */
    public static function parseArray(array $arrayOfPath): array
    {
        foreach ($arrayOfPath as $k => $v) {
            $parsed = self::parse($v);

            if (is_array($parsed)) {
                $arrayOfPath = array_merge($arrayOfPath, $parsed);
            } else {
                $arrayOfPath[$k] = $parsed;
            }
        }

        return $arrayOfPath;
    }

    /**
     * Parse the path and make it uniformly
     *
     * @param string|array $arg
     * @return string|array|null
     */
    public static function parse(string|array $arg): string|array|null
    {
        return match (gettype($arg)) {
            'string' => self::unify($arg),
            'array' => self::parseArray($arg),
            default => null,
        };
    }

    /**
     * Join parts of path to one string
     *
     * @param array $args
     * @return string
     */
    public static function glue(...$args): string
    {
        return join(DIRECTORY_SEPARATOR, array_filter($args));
    }
}
