<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', true);
ini_set('display_startup_errors', true);



/**
 * |-------------------------------------------------
 * | Define some global constants
 * |-------------------------------------------------
 * | Those canstants used in configuration files
 * | Also may be used in some classes
 * | Constant that define a path doesn't have
 * | a trailing backslash
 * |-------------------------------------------------
 */

if (!defined('DIR_SEP'))
    define('DIR_SEP', DIRECTORY_SEPARATOR);

if (!defined('APP_ROOT_PATH'))
    define('APP_ROOT_PATH', realpath(__DIR__ . DIR_SEP . '..'));

if (!defined('CONFIG_PATH'))
    define('CONFIG_PATH', APP_ROOT_PATH . DIR_SEP . 'config');

// Define class root path for autoloader
if (!defined('SCAL_PROJECT_ROOT'))
    define('SCAL_PROJECT_ROOT', APP_ROOT_PATH);



/**
 * |-------------------------------------------------
 * | Register class autoloader
 * |-------------------------------------------------
 * | Here is used Scal autoloader
 * | Written by me
 * |-------------------------------------------------
 */

// Require, set configuration and register autoloader
require_once(APP_ROOT_PATH . DIR_SEP . 'vendor' . DIR_SEP . 'Scal' . DIR_SEP . 'Scal.php');
Scal\Loader::$custom_conf_path = SCAL_PROJECT_ROOT . DIR_SEP . 'Scal.json';
spl_autoload_register('spl_autoload');



/**
 * |-------------------------------------------------
 * | Run bootstrapper
 * |-------------------------------------------------
 * | Bootstrapper disassemble the request
 * | and run the App container
 * |-------------------------------------------------
 */

require_once(APP_ROOT_PATH . DIR_SEP . 'bootstrap' . DIR_SEP . 'app.php');
