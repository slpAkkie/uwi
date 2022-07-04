<?php

namespace Uwi\Core\Routing;

use Uwi\Core\App;
use Uwi\Exceptions\Exception;

class Router
{
    /**
     * HTTP methods in upper case that allowed to be binded
     *
     * @var array
     */
    private static array $routeMethods = [
        'GET', 'POST', 'PUT', 'PATCH', 'DELETE',
    ];

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
     * Indicate if class has booted
     *
     * @var boolean
     */
    public static bool $booted = false;

    /**
     * Initialize the Router
     */
    public function __construct(App $appInstance)
    {
        // Save the App instance
        self::$appInstance = $appInstance;

        // Indicate that class has been booted
        self::$booted = true;
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
        if (!in_array(strtoupper($method), self::$routeMethods)) throw new Exception('Method \'' . $name . '\' not allowed in the App');

        // Create new route with provided arguments
        $router = self::$appInstance->singleton('router', self::class);
        $route = $router->routes[] = self::$appInstance->create(Route::class, $method, ...$arguments);

        // Return create route
        return $route;
    }
}
