<?php

/*
|
|--------------------------------------------------
| Scal
|--------------------------------------------------
|
| General class of Scal.
| Here is all the logic for loading the classes.
|
| Author: Alexandr Shamanin (@slpAkkie)
| Version: 2.1.0
|
*/

namespace Scal;

use Scal\Support\Debug;
use Scal\Support\Path;
use Scal\Support\Str;

class Loader
{
    /**
     * Is Loader has been initialized
     *
     * @var bool
     */
    private static $initialized = false;

    /**
     * Default configuration file name
     *
     * @var string
     */
    private static $conf_file = 'Scal.json';

    /**
     * Custom configuration file name specified manually
     * or null if it's not
     *
     * @var null|string
     */
    public static $custom_conf_path = null;

    /**
     * Namespace paths specified in the configuration file
     *
     * @var array
     */
    private static $NP = [];

    /**
     * Scal Loader options
     *
     * @var array
     */
    private static $OPTIONS = [];

    /**
     * Initialize Scal
     *
     * @return void
     *
     * @throws Scal\Exceptions\ConfigurationNotFoundException
     */
    public static function init(): void
    {
        $conf_file = self::find_conf();

        // The file was not found
        if ($conf_file === null) {
            if (SCAL_EXCEPTION_MODE) throw new Exceptions\ConfigurationNotFoundException();
        } else self::load_conf($conf_file);

        self::normalize_NP();

        self::$initialized = true;
    }

    /**
     * Try to find configuration file
     *
     * @return string|null
     */
    private static function find_conf(): ?string
    {
        $file_name = '';
        $path = '';

        // Custom configuration file was specified
        if (self::$custom_conf_path !== null) {
            self::$custom_conf_path = Path::normalize(self::$custom_conf_path);

            $path = dirname(self::$custom_conf_path);
            $file_name = basename(self::$custom_conf_path);
        }

        // Check if file exists
        if (!file_exists(Path::join($path, $file_name))) {
            // Assume that the file is located in a local directory
            $file_name = self::$conf_file;
            $path = SCAL_EXECUTED_IN;

            // If not, then assume that the file is located in the root directory of Scal
            if (!file_exists(Path::join($path, $file_name))) $path = SCAL_REAL_PATH;
        }

        $file_path = Path::join($path, $file_name);

        // If the file could not be found we will return null
        return file_exists($file_path) ? $file_path : null;
    }

    /**
     * Load the configuration
     *
     * @param string $file Path to configuration path
     * @return void
     *
     * @throws Scal\Exceptions\ConfigurationCannotBeReadException
     */
    private static function load_conf($file): void
    {
        $conf = json_decode(file_get_contents($file), true);

        if (gettype($conf) !== 'array') {
            if (SCAL_EXCEPTION_MODE) throw new Exceptions\ConfigurationCannotBeReadException();
        } else {
            key_exists('np', $conf) && self::$NP = $conf['np'];
            key_exists('options', $conf) && self::$OPTIONS = $conf['options'];
        }
    }

    /**
     * Will make the namespace unified
     *
     * @param string &$namespace
     * @return string
     */
    public static function unify_namespace(&$namespace): string
    {
        return $namespace = trim($namespace, '\\') . '\\';
    }

    /**
     * Normalize NP array
     *
     * @return void
     */
    public static function normalize_NP(): void
    {
        $NP = array();

        foreach (self::$NP as $namespace => $path) {
            self::unify_namespace($namespace);
            if (($path = Path::parse($path)) !== null)
                $NP[$namespace] = $path;
        }

        self::$NP = $NP;
    }

    /**
     * Get path to file of class
     *
     * @param string $namespace Namespace to make path
     * @return string
     */
    public static function get_pathFromNamespace(string $namespace): string
    {
        return Path::join(SCAL_EXECUTED_IN, $namespace);
    }

    /**
     * Get path to file of class
     *
     * @param string $namespace Namespace to make path
     * @return string|array
     */
    public static function get_pathFromConf(string $namespace)
    {
        return gettype(self::$NP[$namespace]) === 'string' ? Path::join(SCAL_EXECUTED_IN, self::$NP[$namespace]) : self::$NP[$namespace];
    }

    /**
     * Extract class namespace and class name
     *
     * @param string $class
     * @return array
     */
    public static function explode_ClassName(string $class): array
    {
        $parts = explode('\\', $class);

        if (count($parts) === 1) {
            $namespace = '';
            $class = $parts[0];
        } else {
            $namespace = join('\\', array_slice($parts, 0, -1));
            self::unify_namespace($namespace);
            $class = array_slice($parts, -1)[0];
        }

        return [$namespace, $class];
    }

    /**
     * Get class file path by namespace and class name
     *
     * @param string $namespace
     * @param string $class
     * @return string
     */
    public static function get_classFile(string $namespace, string $class): string
    {
        // Default
        $path = self::get_pathFromNamespace($namespace);
        $remain_path = '';

        // Try to find in configuration
        foreach (self::$NP as $np => $p)
            if (Str::startsWith($namespace, $np)) {
                $path = self::get_pathFromConf($np);
                $remain_path = Str::slice($namespace, $np);
            }

        return self::check_path($path, $remain_path, $class . '.php');
    }

    /**
     * Check if file exists and return it
     *
     * @param array|string $path
     * @param string $remain
     * @param string $file
     * @return string
     *
     * @throws \Scal\Exceptions\FileNotFoundException
     */
    public static function check_path($path, string $remain, string $file): string
    {
        $file_exist = false;
        $file_path = null;

        switch (gettype($path)) {
            case 'string':
                $file_exist = file_exists($file_path = Path::join($path, $remain, $file));
                break;
            case 'array':
                foreach ($path as $p)
                    if ($file_exist = file_exists($file_path = Path::join($p, $remain, $file))) break;
        }

        if ($file_exist && $file_path) return $file_path;
        if (SCAL_EXCEPTION_MODE) throw new Exceptions\FileNotFoundException();
    }

    /**
     * Class autoloader
     *
     * @param string $class
     * @return void
     *
     * @throws \Scal\Exceptions\ClassNotFoundException
     */
    public static function load(string $class): void
    {
        // Check if not initialized
        if (!self::$initialized) self::init();

        $file_path = self::get_classFile(...self::explode_ClassName($class));
        if (!file_exists($file_path))
            if (SCAL_EXCEPTION_MODE) throw new Exceptions\ClassNotFoundException();
            else return;

        require_once $file_path;

        if (!class_exists($class, false))
            if (SCAL_EXCEPTION_MODE) throw new Exceptions\ClassNotFoundException();
            else return;
    }
}
