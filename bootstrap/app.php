<?php

use Uwi\Core\App;
use Uwi\Core\Routing\Router;
use Uwi\Support\FileSystem\FileSystem;
use Uwi\Support\Path\Path;

// Load functions
foreach (FileSystem::getFiles(Path::glue(VENDOR_UWI_PATH, 'Functions')) as $functionsFile) {
    include_once($functionsFile);
}

// Create the App
$app = new App();

// Create Router instance and load routes
$app->singleton('router', Router::class);
require_once(Path::glue(APP_ROOT_PATH, 'routes', 'web.php'));

// Run the Application
$app->run();
