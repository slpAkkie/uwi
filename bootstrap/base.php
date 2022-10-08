<?php

/**
 * |-------------------------------------------------
 * | Show all errors during execution.
 * |-------------------------------------------------
 * | Enable php error reports to show all errors
 * | that will be issued anywhere in the application.
 * |
 */
ini_set('error_reporting', E_ALL);
ini_set('display_errors', true);
ini_set('display_startup_errors', true);
ini_set('html_errors', true);



/**
 * |-------------------------------------------------
 * | Define app base path.
 * |-------------------------------------------------
 * | Those constants may be used in configuration
 * | files and some classes.
 * |
 * | ! Constants that define a path doesn't have
 * | a trailing backslash.
 * |
 */

if (!defined('APP_BASE_PATH')) {
    define('APP_BASE_PATH', realpath(__DIR__ . '/..'));
}



/**
 * |-------------------------------------------------
 * | Register class autoloader.
 * |-------------------------------------------------
 * | Use customized Scal.
 * |
 */

require_once APP_BASE_PATH . '/core/Dependencies/Autoload/Initializer.php';



/**
 * |-------------------------------------------------
 * | Define Uwi constants.
 * |-------------------------------------------------
 * | Those constants may be used in configuration
 * | files and some classes.
 * |
 * | ! Constants that define a path doesn't have
 * | a trailing backslash.
 * |
 */

if (!defined('CONFIG_PATH')) {
    define('CONFIG_PATH', APP_BASE_PATH . '/config');
}

if (!defined('CORE_PATH')) {
    define('CORE_PATH', APP_BASE_PATH . '/core');
}



/**
 * |-------------------------------------------------
 * | Load Helpers.
 * |-------------------------------------------------
 * | Load support functions that make it easy to use
 * | some of application features.
 * |
 */

if (!defined('HELPERS_PATH')) {
    define('HELPERS_PATH', CORE_PATH . '/Helpers');
} {
    $helpersPath = [
        APP_BASE_PATH . '/core/Helpers',
        APP_BASE_PATH . '/app/Helpers',
    ];

    foreach ($helpersPath as $path) {
        if (!file_exists($path)) {
            continue;
        }

        foreach (scandir($path) as $entry) {
            if (!is_dir("$path/$entry")) {
                include_once "$path/$entry";
            }
        }
    }
}