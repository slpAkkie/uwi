<?php

use Uwi\Foundation\Application;
use Uwi\Foundation\ApplicationServiceLoader;
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
    // Application.
    ApplicationServiceLoader::class,

    // Kernel.
    HttpKernelServiceLoader::class,
]);

$app->start();
