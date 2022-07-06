<?php

use Uwi\Filesystem\Path;
use Uwi\Foundation\Application;

try {
    // Create the App
    (new Application())
        // Load all dependencies from configuration
        ->loadDependencies()
        // Run the Application
        ->run();
} catch (Exception $e) {
    // TODO: Replace to return a Response instance
    ob_start();
    (function () use ($e) {
        include_once(Path::glue(UWI_FRAMEWORK_PATH, 'Exceptions', 'views', '500.tpl.php'));
    })();
    flush();
}
