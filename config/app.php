<?php

/**
 * |-------------------------------------------------
 * | Application configuration
 * |-------------------------------------------------
 * | Here are base application configuration
 * | You can change all of the parameters
 * |-------------------------------------------------
 */

return [

    'name' => env('APP_NAME', 'Uwi'),

    'providers' => [
        Uwi\Sessions\SessionServiceProvider::class,
        Uwi\Foundation\Http\Routing\Providers\RouterServiceProvider::class,
        Uwi\Foundation\Providers\AppServiceProvider::class,
    ],

];
