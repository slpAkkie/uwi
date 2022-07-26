<?php

use Uwi\Services\Http\Routing\Facades\Route;

Route::get('/', [\App\Http\Controllers\Controller::class, 'welcome']);
