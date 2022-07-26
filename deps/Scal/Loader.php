<?php

namespace Scal;

use Exception;
use Scal\Support\Path;
use Throwable;

class Loader
{
    /**
     * Is Loader has been booted
     *
     * @var bool
     */
    private static $booted = false;

    /**
     * Default configuration file name
     *
     * @var string
     */
    private const DEFAULT_CONFIGURATION_FILENAME = 'Scal.json';

    /**
     * Namespace map from configuration file
     *
     * @var array
     */
    private static $namespaceMapping = [];

    /**
     * Try to get configuration file
     *
     * @return string|null
     */
    private static function getConfigurationFile(): ?string
    {
        $configurationFile = Path::glue(APP_BASE_PATH, self::DEFAULT_CONFIGURATION_FILENAME);

        if (!file_exists($configurationFile)) {
            $configurationFile = Path::glue(SCAL_ROOT_PATH, self::DEFAULT_CONFIGURATION_FILENAME);

            if (!file_exists($configurationFile)) {
                $configurationFile = null;
            }
        }

        return $configurationFile;
    }

    /**
     * Load the configuration from specified file
     *
     * @param string $file
     * @return array
     * 
     * @throws Exception
     */
    private static function loadConfiguration(string $file): array
    {
        $namespaceMapping = json_decode(file_get_contents($file), true);

        if (gettype($namespaceMapping) !== 'array') {
            throw new Exception('Configuration file should contain an array');
        }

        if (key_exists('mapping', $namespaceMapping)) {
            return $namespaceMapping['mapping'];
        }
    }

    /**
     * Boot Scal
     *
     * @return void
     * 
     * @throws Exception
     */
    public static function boot(): void
    {
        // Get configuration file
        $configurationFile = self::getConfigurationFile();
        $namespaceMapping = [];

        // Load the configuration
        if ($configurationFile !== null) {
            $namespaceMapping = self::loadConfiguration($configurationFile);
        }

        // Unify mapping
        self::$namespaceMapping = self::unifyMapping($namespaceMapping);

        // Indicate that Loader has been booted
        self::$booted = true;
    }

    /**
     * Unify namespace mapping
     *
     * @return array
     */
    public static function unifyMapping(array $rawNamespaceMapping): array
    {
        $namespaceMapping = [];
        foreach ($rawNamespaceMapping as $namespace => $path) {
            $parsedPath = gettype($path) === 'array'
                ? Path::parse($path)
                : Path::parse(Path::glue(APP_BASE_PATH, $path));
            if (!$parsedPath) continue;

            $namespaceMapping[$namespace] = $parsedPath;
        }

        return $namespaceMapping;
    }

    /**
     * Get path to file of class
     *
     * @param string $namespace
     * @return string
     */
    public static function getPathByNamespace(string $namespace): string
    {
        return Path::glue(APP_BASE_PATH, $namespace);
    }

    /**
     * Get path to file of class
     *
     * @param string $namespace
     * @return string|array
     */
    public static function getPathByConfiguration(string $namespace): string|array
    {
        return self::$namespaceMapping[$namespace];
    }

    /**
     * Extract class namespace and class name
     *
     * @param string $class
     * @return array
     */
    public static function explodeClassName(string $class): array
    {
        $exploded = explode(NAMESPACE_SEPARATOR, $class);

        if (count($exploded) === 1) {
            $namespace = '';
            $class = $exploded[0];
        } else {
            $namespace = join(NAMESPACE_SEPARATOR, array_slice($exploded, 0, -1)) . NAMESPACE_SEPARATOR;
            $class = $exploded[count($exploded) - 1];
        }

        return [
            $namespace,
            $class,
        ];
    }

    /**
     * Get class file path by namespace and class name
     *
     * @param string $namespace
     * @param string $class
     * @return string
     */
    public static function getClassFile(string $classNamespace, string $class): string
    {
        // Try to get path by default
        $path = self::getPathByNamespace($classNamespace);
        $remainPath = '';

        // Try to find in configuration
        foreach (self::$namespaceMapping as $namespacePart => $v) {
            if (str_starts_with($classNamespace, $namespacePart)) {
                $path = $v;
                $remainPath = Path::unify(substr($classNamespace, strlen($namespacePart)));
            }
        }

        // Check if file exists and return it
        $path = self::findClassFile($path, $remainPath, $class . '.php');

        if ($path === null) {
            throw new Exception('Class file \'' . Path::glue($path, $remainPath, $class . '.php') . '\' not found');
        }

        return $path;
    }

    /**
     * Check if file exists and return it
     *
     * @param array|string $path
     * @param string $remain
     * @param string $file
     * @return ?string
     * 
     * @throws Exception
     */
    public static function findClassFile(string|array $path, string $remain, string $file): ?string
    {
        return match (gettype($path)) {
            // If Path is a string try to find class file here
            'string' => (function () use ($path, $remain, $file) {
                if (file_exists($filePath = Path::glue($path, $remain, $file))) {
                    return $filePath;
                }
            })(),
            // Or if it is an array check all possible directories
            'array' => (function () use ($path, $remain, $file) {
                foreach ($path as $p) {
                    if (file_exists($filePath = Path::glue($p, $remain, $file))) {
                        return $filePath;
                    }
                }
            })(),
        };
    }

    /**
     * Class autoloader
     *
     * @param string $class
     * @return void
     * 
     * @throws Exception
     */
    public static function load(string $class): void
    {
        // Check if Scal not booted
        if (!self::$booted) {
            self::boot();
        }

        $file_path = '';

        try {
            // Get file for class
            $file_path = self::getClassFile(...self::explodeClassName($class));
            if (!file_exists($file_path)) {
                throw new Exception('Class not found');
            }
        } catch (Throwable $e) {
            return;
        }

        require_once($file_path);
    }
}
