<?php

use App\Http\Controllers\Controller;
use Services\Http\Contracts\Routing\RouteContract;
use Services\Http\Contracts\Routing\RouterContract;

/** @var \Services\Http\Contracts\Routing\RouterContract */
$router = app()->get(RouterContract::class);



$router->addRoute(app()->new(RouteContract::class, 'get', '/', [Controller::class, 'welcome']));
