<?php

use Uwi\Filesystem\Path;

ini_set('error_reporting', E_ALL);
ini_set('display_errors', true);
ini_set('display_startup_errors', true);



/**
 * |-------------------------------------------------
 * | Define root path constant
 * |-------------------------------------------------
 * | ! Constant that define a path doesn't have
 * | a trailing backslash
 * |-------------------------------------------------
 */

if (!defined('DIR_SEP'))
    define('DIR_SEP', DIRECTORY_SEPARATOR);

if (!defined('APP_BASE_PATH'))
    define('APP_BASE_PATH', realpath(__DIR__ . DIR_SEP . '..'));

/**
 * |-------------------------------------------------
 * | Define Scal constants
 * |-------------------------------------------------
 * | Those constants needed for correct work
 * | of Scal autoloader
 * |-------------------------------------------------
 */
if (!defined('SCAL_EXCEPTION_MODE'))
    define('SCAL_EXCEPTION_MODE', true);

if (!defined('SCAL_PROJECT_ROOT'))
    define('SCAL_PROJECT_ROOT', APP_BASE_PATH);



/**
 * |-------------------------------------------------
 * | Register class autoloader
 * |-------------------------------------------------
 * | Here is used Scal autoloader
 * | Written by me
 * |-------------------------------------------------
 */

require_once(APP_BASE_PATH . DIR_SEP . 'vendor' . DIR_SEP . 'Scal' . DIR_SEP . 'Scal.php');



/**
 * |-------------------------------------------------
 * | Define Uwi constants
 * |-------------------------------------------------
 * | Those constants may be used in configuration
 * | files and some classes
 * |
 * | ! Constant that define a path doesn't have
 * | a trailing backslash
 * |-------------------------------------------------
 */

if (!defined('CONFIG_PATH'))
    define('CONFIG_PATH', Path::glue(APP_BASE_PATH, 'config'));

if (!defined('VENDOR_PATH'))
    define('VENDOR_PATH', Path::glue(APP_BASE_PATH, 'vendor'));

if (!defined('UWI_FRAMEWORK_PATH'))
    define('UWI_FRAMEWORK_PATH', Path::glue(VENDOR_PATH, 'Uwi', 'Framework'));



/**
 * |-------------------------------------------------
 * | Run bootstrapper
 * |-------------------------------------------------
 * | Bootstrapper disassemble the request
 * | and run the App container
 * |-------------------------------------------------
 */

require_once(Path::glue(APP_BASE_PATH, 'bootstrap', 'app.php'));
