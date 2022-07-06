<?php

namespace Uwi\Filesystem;

use Uwi\Exceptions\NotFoundException;

class Filesystem
{
    /**
     * Get files from the directory
     *
     * @param string $path
     * @return array
     * 
     * @throws FileNotFoundException
     */
    public static function getFiles(string $path): array
    {
        if (!file_exists($path)) throw new NotFoundException('Directory for \'' . $path . '\' not found');

        return array_reduce(scandir($path), function (array $files, string $entry) use ($path) {
            if (!is_dir($entry)) $files[] = Path::glue($path, $entry);

            return $files;
        }, []);
    }

    /**
     * Get file name according to specified string
     *
     * @param string $file
     * @return string
     * 
     * @throws FileNotFoundException
     */
    public static function getFileName(string $file): string
    {
        if (!is_file($file)) throw new NotFoundException('File \'' . $file . '\' not found');

        $fileName = array_slice(explode(DIRECTORY_SEPARATOR, $file), -1, 1)[0];

        return $fileName;
    }

    /**
     * Get file name withour extension according to specified string
     *
     * @param string $file
     * @return string
     * 
     * @throws FileNotFoundException
     */
    public static function getFileNameWithoutExtention(string $file): string
    {
        $fullName = self::getFileName($file);
        $fileName = join('.', array_slice(explode('.', $fullName), 0, -1));

        return $fileName;
    }
}
