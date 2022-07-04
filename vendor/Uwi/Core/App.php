<?php

namespace Uwi\Core;

use Uwi\Exceptions\Exception;
use Uwi\Exceptions\NotFoundException;
use Uwi\Support\FileSystem\FileSystem;

class App
{
    /**
     * Array of all configuration files
     *
     * @var array
     */
    private array $config = [];

    /**
     * List of singleton classes
     *
     * @var array
     */
    private array $singletons = [];

    /**
     * Initialize the App
     */
    public function __construct()
    {
        // Load all configurations
        $configFiles = FileSystem::getFiles(CONFIG_PATH);
        foreach ($configFiles as $configFile) {
            $configKey = FileSystem::getFileNameWithoutExtention($configFile);
            $this->config[$configKey] = include_once($configFile);
        }
    }

    /**
     * Get loaded config by specified key
     *
     * @param string $key
     * @throws FileNotFoundException
     * @return array
     */
    public function getConfig(string $key): array
    {
        // Check if config with provided key extists
        if (!key_exists($key, $this->config)) {
            throw new NotFoundException('Config key \'' . $key . '\' not found');
        }

        return $this->config[$key];
    }

    /**
     * Instantiate new singleton class
     *
     * @param string $className
     * @param string $key
     * @return mixed
     */
    public function singleton(string $key, ?string $className = null): mixed
    {
        // Check if class already booted
        // and saved to the App instance
        if (key_exists($key, $this->singletons)) {
            return $this->singletons[$key];
        } else if (!$className) {
            return null;
        }

        // Check if it already booted
        // but wasn't saved to the App instance
        if (property_exists($className, 'booted') && $className::$booted) {
            throw new Exception('Class \'' . $className . '\' already has been booted');
        }

        // Instantiate class and save it with a key
        return $this->singletons[$key] = new $className($this);
    }

    /**
     * 
     * Create new instance and inject App instance into it
     *
     * @param string $className
     * @param array $arguments
     * @return mixed
     */
    public function create(string $className, ...$arguments): mixed
    {
        $instance = new $className($this, ...$arguments);

        return $instance;
    }

    /**
     * Run the Application
     *
     * @return void
     */
    public function run(): void
    {
        // TODO: Implement...
    }
}
