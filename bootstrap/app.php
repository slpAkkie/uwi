<?php

use Uwi\Filesystem\Path;
use Uwi\Foundation\Application;

// TODO: Use set_error_handler

try {
    // Create the App
    (new Application())
        // Run the Application
        ->run();
} catch (Throwable $e) {
    // TODO: Replace to return a Response instance
    ob_start();
    (function () use ($e) {
        include_once(Path::glue(UWI_FRAMEWORK_PATH, 'Exceptions', 'views', '500.tpl.php'));
    })();
    flush();
}
