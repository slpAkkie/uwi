<?php

define('APP_ROOT_PATH', realpath('../'));



require_once APP_ROOT_PATH . '/bootstrap/app.php';
require_once APP_ROOT_PATH . '/Framework/Autoload/Initializer.php';

$application = new \Framework\Foundation\Application([
    /** Order is important if one of the services depends on other one */
    \Services\DotenvServiceLoader::class,
    \Services\CalibriServiceLoader::class,
    \Services\HttpServiceLoader::class,
]);

$application->run();
