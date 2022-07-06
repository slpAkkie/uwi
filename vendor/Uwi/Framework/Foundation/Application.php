<?php

namespace Uwi\Foundation;

use ReflectionMethod;
use Uwi\Contracts\Http\Routing\RouterContract;
use Uwi\Exceptions\NotFoundException;
use Uwi\Filesystem\Filesystem;
use Uwi\Filesystem\Path;
use Uwi\Foundation\Http\Request\Request;
use Uwi\Foundation\Providers\ServiceProvider;

class Application extends Container
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
     * Initialize the App
     */
    public function __construct()
    {
        (self::$instance = $this)
            ->loadHelpers()
            ->loadConfiguration()

            ->registerServiceProviders();
    }

    /**
     * Load helpers
     *
     * @return static
     */
    private function loadHelpers(): static
    {
        foreach (Filesystem::getFiles(Path::glue(UWI_FRAMEWORK_PATH, 'Foundation', 'Helpers')) as $helpersFile) {
            include_once($helpersFile);
        }

        return $this;
    }

    /**
     * Load configurations
     *
     * @return static
     */
    private function loadConfiguration(): static
    {
        $configFiles = FileSystem::getFiles(CONFIG_PATH);
        foreach ($configFiles as $configFile) {
            $configKey = FileSystem::getFileNameWithoutExtention($configFile);
            $this->config[$configKey] = include_once($configFile);
        }

        return $this;
    }

    /**
     * Register service providers specified in configuration
     *
     * @return static
     */
    private function registerServiceProviders(): static
    {
        /** @var ServiceProvider[] */
        $serviceProviders = [];
        foreach ($this->getConfig('app', 'providers', []) as $serviceProvider) {
            $serviceProviders[] = $this->registerServiceProvider($serviceProvider);
        }

        foreach ($serviceProviders as $serviceProvider) {
            $serviceProvider->boot();
            $serviceProvider->booted = true;
        }

        return $this;
    }

    /**
     * Register the service provider
     *
     * @param string $serviceProviderClass
     * @return ServiceProvider
     */
    public function registerServiceProvider(string $serviceProviderClass): ServiceProvider
    {
        /** @var ServiceProvider */
        $serviceProvider = $this->instantiate($serviceProviderClass);

        foreach ($serviceProvider->bindings as $abstract => $concrete) {
            $this->bind($abstract, $concrete);
        }

        $serviceProvider->register();
        $serviceProvider->registered = true;

        return $serviceProvider;
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
     * Run the Application
     *
     * @return void
     */
    public function run(): void
    {
        // Initialize a Request object
        $this->request = $this->singleton(Request::class);

        // Identify Route and run the controller
        $currentRoute = $this->singleton(RouterContract::class)->getCurrentRoute();

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
                $methodArgs[] = app()->instantiate($paramType);
            } else if ($paramType === Request::class) {
                $methodArgs[] = app()->request;
            } else {
                $methodArgs[] = null;
            }
        }

        return $methodArgs;
    }
}
