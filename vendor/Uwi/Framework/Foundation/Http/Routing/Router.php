<?php

namespace Uwi\Foundation\Http\Routing;

use Uwi\Contracts\Http\Routing\RouteContract;
use Uwi\Contracts\Http\Routing\RouterContract;
use Uwi\Exceptions\Exception;
use Uwi\Filesystem\Path;
use Uwi\Foundation\Http\Exceptions\HttpNotFoundException;
use Uwi\Foundation\Http\Exceptions\MethodNotAllowedException;
use Uwi\Foundation\Http\Routing\URL;

class Router implements RouterContract
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
     * List of routes
     *
     * @var array
     */
    private array $routes = [];

    /**
     * Calls when singleton has been instantiated and saved
     *
     * @return void
     */
    public function boot(): void
    {
        $this
            ->loadConfiguration()
            ->loadRoutes();
    }

    private function loadConfiguration(): static
    {
        // Load configuration
        $this->routesPath = app()->getConfig(
            'router.routes_path',
            self::DEFAULT_ROUTES_PATH
        );

        $this->routesFile = app()->getConfig(
            'router.routes_file',
            self::DEFAULT_ROUTES_FILE
        );

        return $this;
    }

    private function loadRoutes(): static
    {
        include_once(Path::glue(
            APP_BASE_PATH,
            $this->routesPath,
            $this->routesFile
        ));

        return $this;
    }

    /**
     * Get current requested route
     *
     * @return Route
     * 
     * @throws HttpNotFoundException|MethodNotAllowedException
     */
    public function getCurrentRoute(): Route
    {
        foreach ($this->routes as $route) {
            if (URL::compare($route->uri, app()->request->uri)) {
                if (!app()->request->method === $route->method) {
                    throw new MethodNotAllowedException('Method [' . app()->request->method . '] not allowed for route [' . $route->uri . ']');
                }

                return $route;
            }
        }

        throw new HttpNotFoundException('Requested URI [' . app()->request->uri . '] not found');
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
            throw new Exception('Method [' . $name . '] not allowed in the App');
        }

        // Create new route with provided arguments
        $router = app()->singleton(RouterContract::class);
        $route = $router->routes[] = app()->instantiate(RouteContract::class, $method, ...$arguments);

        // Return create route
        return $route;
    }
}
