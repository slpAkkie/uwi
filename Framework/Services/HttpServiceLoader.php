<?php

namespace Services;

use Framework\Foundation\Contracts\ApplicationContract;
use Framework\Foundation\Contracts\ServiceLoaderContract;
use Services\Http\Contracts\Routing\RouterContract;
use Services\Http\Contracts\Sessions\SessionContract;
use Services\Http\Contracts\Cookies\CookieContract;
use Services\Http\Contracts\Requests\RequestContract;
use Services\Http\Contracts\Routing\RouteContract;
use Services\Http\Routing\Router;
use Services\Route;

class HttpServiceLoader implements ServiceLoaderContract
{
    protected const DEAFULT_ROUTES_PATH = '/routes';
    protected const DEAFULT_ROUTES_FILE = 'routes.php';

    /**
     * TODO: Undocumented variable
     *
     * @var array<string, string>
     */
    protected array $bindings = [
        RouteContract::class => Route::class,
    ];

    /**
     * TODO: Undocumented variable
     *
     * @var array<string, string>
     */
    protected array $bindingsAndShare = [
        CookieContract::class => Cookie::class,
        SessionContract::class => Session::class,
        RequestContract::class => Request::class,
        RouterContract::class => Router::class,
    ];

    /**
     * TODO: Undocumented function
     *
     * @param \Framework\Foundation\Contracts\ApplicationContract $app
     * @return void
     */
    public function register(ApplicationContract $app): void
    {
        foreach (array_merge($this->bindings, $this->bindingsAndShare) as $abstract => $concrete) {
            $app->bind($abstract, $concrete);
        }
    }

    /**
     * TODO: Undocumented function
     *
     * @param \Framework\Foundation\Contracts\ApplicationContract $app
     * @return void
     */
    public function boot(ApplicationContract $app): void
    {
        foreach (array_keys($this->bindingsAndShare) as $abstract) {
            $app->share($app->new($abstract));
        }

        require_once APP_ROOT_PATH . self::DEAFULT_ROUTES_PATH . '/' . self::DEAFULT_ROUTES_FILE;
    }
}
