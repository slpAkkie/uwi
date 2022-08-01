<?php

use Uwi\Services\Http\Routing\Facades\Route;

/**
 * |-------------------------------------------------
 * | Web routes.
 * |-------------------------------------------------
 * | Here are placed your application routes.
 * | Use Route facade to add new route, specify the
 * | path and the handler by passing array with
 * | controller and method name.
 * | Now create something incredible.
 * |
 */

Route::get('/', [\App\Http\Controllers\Controller::class, 'welcome']);
Route::get('/calibri', [\App\Http\Controllers\Controller::class, 'calibri']);
