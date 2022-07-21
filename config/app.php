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

    /**
     * |-------------------------------------------------
     * | Application name
     * | Used in page title and other places where it's nedeed
     * | You may also use this
     * | */
    'name' => env('APP_NAME', 'Uwi'),

    /**
     * |-------------------------------------------------
     * | Application language
     * | which will be used in the internal processes
     * | */
    'lang' => 'en',

    /**
     * |-------------------------------------------------
     * | Serivice providers that should be loaded with application start
     * | */
    'providers' => [
        Uwi\Sessions\SessionServiceProvider::class,
        Uwi\Database\Lion\LionServiceProvider::class,
        Uwi\Foundation\Http\Routing\Providers\RouterServiceProvider::class,
        Uwi\Foundation\Providers\AppServiceProvider::class,
    ],

];
