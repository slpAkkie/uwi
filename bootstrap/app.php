<?php

use Uwi\Core\App;
use Uwi\Support\Path\Path;

try {
    // Create the App
    (new App())
        // Load all dependencies from configuration
        ->loadDependencies()
        // Run the Application
        ->run();
} catch (Exception $e) {
    // TODO: Replace to return a Response instance
    ob_start();
    include_once(Path::glue(VENDOR_UWI_PATH, 'views', 'errors', 'exception.tpl.php'));
    flush();
}
