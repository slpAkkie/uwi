<?php

use Uwi\Foundation\Application;
use Uwi\Services\Application\ApplicationServiceLoader;
use Uwi\Services\Database\Lion\LionServiceLoader;
use Uwi\Services\Dotenv\DotenvServiceLoader;
use Uwi\Services\Http\HttpKernelServiceLoader;

/**
 * |-------------------------------------------------
 * | Global Exception Handler.
 * |-------------------------------------------------
 * | Set primary global handler for all exceptions
 * | that may occur in the operation of the application.
 * | After the Application loaded another handler
 * | will be registered.
 * |
 */

set_exception_handler('ddException');


/**
 * |-------------------------------------------------
 * | Instantiate an Application.
 * |-------------------------------------------------
 * | Create new instance of Application and load
 * | Services that specified.
 * |
 */

$app = Application::create([
    // Application dependencies.
    DotenvServiceLoader::class,

    // Application Kernel.
    HttpKernelServiceLoader::class,
]);

$app->start();
