<?php

/**
 * |-------------------------------------------------
 * | Route list
 * |-------------------------------------------------
 * | Here you can define routes for your application
 * |-------------------------------------------------
 */

use Uwi\Core\Routing\Router;

Router::get('/', [App\Http\Controllers\Controller::class, 'welcome'])->name('welcome');
