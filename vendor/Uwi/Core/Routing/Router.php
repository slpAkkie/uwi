<?php

namespace Uwi\Core\Routing;

use App\Exceptions\HttpNotFoundException;
use Uwi\Core\App;
use Uwi\Exceptions\Exception;
use Uwi\Support\Path\Path;

class Router
{
    /**
     * HTTP methods in upper case that allowed to be binded
     *
     * @var array
     */
    private const HTTP_METHODS = [
        'GET', 'POST', 'PUT', 'PATCH', 'DELETE',
    ];

    /**
     * Configuration key
     * 
     * @var string
     */
    private const CONFIG_KEY = 'router';

    /**
     * Default routes path
     *
     * @var string
     */
    private const DEFAULT_ROUTES_PATH = 'routes';

    /**
     * Default routes file
     *
     * @var string
     */
    private const DEFAULT_ROUTES_FILE = 'web.php';

    /**
     * Path to folder with routes file
     * Relative to App root directory
     *
     * @var string
     */
    private string $routesPath;

    /**
     * File with routes definition
     *
     * @var string
     */
    private string $routesFile;

    /**
     * App Instance
     *
     * @var ?App
     */
    private static ?App $appInstance = null;

    /**
     * List of routes
     *
     * @var array
     */
    private array $routes = [];

    /**
     * Indicate if class has instantiated
     *
     * @var boolean
     */
    public static bool $isInstantiated = false;

    /**
     * Indicate if class has booted
     *
     * @var boolean
     */
    public static bool $isBooted = false;

    /**
     * Initialize the Router
     */
    public function __construct(App $appInstance)
    {
        // Save the App instance
        self::$appInstance = $appInstance;



        // Load configuration
        $this->routesPath = self::$appInstance->getConfig(
            self::CONFIG_KEY,
            'routes_path',
            self::DEFAULT_ROUTES_PATH
        );

        $this->routesFile = self::$appInstance->getConfig(
            self::CONFIG_KEY,
            'routes_file',
            self::DEFAULT_ROUTES_FILE
        );



        // Indicate that class has been instantiated
        self::$isInstantiated = true;
    }

    /**
     * Method that will be invoked after all dependencies has been instantiated
     *
     * @return self
     */
    public function boot(): self
    {
        // Exit if class has been booted already
        if (self::$isBooted) return $this;

        // Include routes file with routes definition
        include_once(Path::glue(
            APP_ROOT_PATH,
            $this->routesPath,
            $this->routesFile
        ));



        // Indicate that class has been booted
        self::$isBooted = true;

        return $this;
    }

    /**
     * Get current requested route
     *
     * @throws HttpNotFoundException
     * @return Route
     */
    public function getCurrentRoute(): Route
    {
        foreach ($this->routes as $route) {
            // TODO: Find requested route
        }

        throw new HttpNotFoundException('Requested URL not found');
    }

    /**
     * Handle static calls
     * Expect to call something named like http method
     *
     * @param string $name
     * @param array $arguments
     * @return void
     */
    public static function __callStatic(string $name, array $arguments): Route
    {
        // Transform called name to upper case
        // and check if such method in allowed list
        $method = strtoupper($name);
        if (!in_array(strtoupper($method), self::HTTP_METHODS)) {
            throw new Exception('Method \'' . $name . '\' not allowed in the App');
        }

        // Create new route with provided arguments
        $router = self::$appInstance->singleton('router', self::class);
        $route = $router->routes[] = self::$appInstance->create(Route::class, $method, ...$arguments);

        // Return create route
        return $route;
    }
}
