<?php

/**
 * |-------------------------------------------------
 * | Register class autoloader
 * |-------------------------------------------------
 * | Probably should be replaced with
 * | composer psr-4 autoload
 * |-------------------------------------------------
 */

spl_autoload_register('spl_autoload');



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
