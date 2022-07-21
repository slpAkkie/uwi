<?php

/**
 * |-------------------------------------------------
 * | Database connection configuration
 * |-------------------------------------------------
 * | Here are base db connection configuration
 * | You can change all of the parameters
 * |-------------------------------------------------
 */

return [

    /**
     * |-------------------------------------------------
     * | Database connection cridentials
     * | to connect db host
     * | */
    'connection' => [
        'host' => env('DATABASE_HOST', 'localhost'),
        'port' => env('DATABASE_PORT', '3306'),
        'dbname' => env('DATABASE_NAME', 'force'),
        'user' => env('DATABASE_USER', 'root'),
        'password' => env('DATABASE_PASSWORD', ''),
        'charset' => env('DATABASE_CHARSET', 'utf8'),
    ],

];
