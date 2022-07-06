<?php

namespace Uwi\Foundation;

use ReflectionMethod;
use Uwi\Exceptions\Exception;
use Uwi\Exceptions\NotFoundException;
use Uwi\Filesystem\Filesystem;
use Uwi\Filesystem\Path;
use Uwi\Foundation\Http\Request\Request;

class Application
{
    /**
     * App instance
     *
     * @var Application
     */
    public static Application $instance;

    /**
     * Request object
     *
     * @var Request
     */
    public readonly Request $request;

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
        // Load helpers
        foreach (Filesystem::getFiles(Path::glue(UWI_FRAMEWORK_PATH, 'Foundation', 'Helpers')) as $helpersFile) {
            include_once($helpersFile);
        }

        // Load all configurations
        $configFiles = FileSystem::getFiles(CONFIG_PATH);
        foreach ($configFiles as $configFile) {
            $configKey = FileSystem::getFileNameWithoutExtention($configFile);
            $this->config[$configKey] = include_once($configFile);
        }

        // Create Request
        $this->request = $this->create(Request::class);

        // Save it's instance
        self::$instance = $this;
    }

    /**
     * Get loaded config by specified configuration name
     *
     * @param string $configurationName
     * @param ?string $key
     * @param mixed $default
     * @return mixed
     * 
     * @throws NotFoundException
     */
    public function getConfig(string $configurationName, ?string $key = null, mixed $default = null): mixed
    {
        // Check if config with provided key extists
        if (!key_exists($configurationName, $this->config)) {
            throw new NotFoundException('Config key \'' . $configurationName . '\' not found');
        }

        $config = $this->config[$configurationName];

        // Check if key provided
        if ($key !== null) {
            // Check if key exists in the configuration
            if (!key_exists($key, $config)) {
                // Check if default value specified
                if ($default !== null) {
                    return $default;
                }

                throw new NotFoundException('Config key \'' . $configurationName . '\' not found');
            }

            return $config[$key];
        }

        return $config;
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
        // Check if class already instantiated
        // and saved to the App instance
        if (key_exists($key, $this->singletons)) {
            return $this->singletons[$key];
        } else if (!$className) {
            return null;
        }

        // Check if it already instantiated
        // but wasn't saved to the App instance
        if (property_exists($className, 'isInstantiated') && $className::$isInstantiated) {
            throw new Exception('Class \'' . $className . '\' already has been instantiated');
        }

        // Instantiate class and save it with a key
        return $this->singletons[$key] = new $className();
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
        $instance = new $className(...$arguments);

        return $instance;
    }

    /**
     * Load dependencies according to config
     *
     * @return self
     */
    public function loadDependencies(): self
    {
        foreach ($this->getConfig('app', 'dependencies', []) as $key => $className) {
            $this->singleton($key, $className);
        }

        return $this;
    }

    /**
     * Run the Application
     *
     * @return void
     */
    public function run(): void
    {
        // Boot dependencies
        foreach ($this->singletons as $singleton) {
            $singleton->boot();
        }

        // Identify Route and run the controller
        $currentRoute = $this->singleton('router')->getCurrentRoute();

        // Examine method parameters and resolve them
        $controllerClass = $currentRoute->controllerClass;
        $controller = new $controllerClass();

        // Run the controller method with collected parameters
        $controller->{$currentRoute->controllerMethod}(
            ...$this->resolveControllerMethodParameter($controllerClass, $currentRoute->controllerMethod)
        );
    }

    /**
     * Collect and instantiate controller method parameters
     *
     * @param string $controller
     * @param string $method
     * @return array
     */
    private function resolveControllerMethodParameter(string $controller, string $method): array
    {
        $reflectedControllerMethod = new ReflectionMethod($controller, $method);
        $reflectedMethodParameters = $reflectedControllerMethod->getParameters();


        // Collect method parameters
        // ! Support Request extended class as parameter only
        $methodArgs = [];

        foreach ($reflectedMethodParameters as $reflectedParameter) {
            $paramType = $reflectedParameter->getType()->getName();

            if (is_subclass_of($paramType, Request::class)) {
                $methodArgs[] = app()->create($paramType);
            } else if ($paramType === Request::class) {
                $methodArgs[] = app()->request;
            } else {
                $methodArgs[] = null;
            }
        }

        return $methodArgs;
    }
}
