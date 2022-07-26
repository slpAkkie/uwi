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



/**
 * |-------------------------------------------------
 * | Define app base path.
 * |-------------------------------------------------
 * | This path doesn't have a trailing backslash.
 * |
 */

if (!defined('APP_BASE_PATH')) {
    define('APP_BASE_PATH', realpath(__DIR__ . DIRECTORY_SEPARATOR . '..'));
}



/**
 * |-------------------------------------------------
 * | Register class autoloader.
 * |-------------------------------------------------
 * | Include class autoloader - Scal.
 * |
 */

require_once(APP_BASE_PATH . DIRECTORY_SEPARATOR . 'deps' . DIRECTORY_SEPARATOR . 'Scal' . DIRECTORY_SEPARATOR . 'Scal.php');



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
    define('CONFIG_PATH', APP_BASE_PATH . DIRECTORY_SEPARATOR . 'config');
}

if (!defined('CORE_PATH')) {
    define('CORE_PATH', APP_BASE_PATH . DIRECTORY_SEPARATOR . 'core');
}



/**
 * |-------------------------------------------------
 * | Load Helpers.
 * |-------------------------------------------------
 * | Load support functions that make it easy to use
 * | some of application features.
 * |
 */

include_once(CORE_PATH . DIRECTORY_SEPARATOR . 'Helpers' . DIRECTORY_SEPARATOR . 'debug.php');



/**
 * |-------------------------------------------------
 * | Run bootstrapper.
 * |-------------------------------------------------
 * | To initialize and run the Application.
 * |
 */

require_once(APP_BASE_PATH . DIRECTORY_SEPARATOR . 'bootstrap' . DIRECTORY_SEPARATOR . 'app.php');
