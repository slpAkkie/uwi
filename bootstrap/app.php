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
($app = new App())
    // Load all dependencies from configuration
    ->loadDependencies()
    // Run the Application
    ->run();
