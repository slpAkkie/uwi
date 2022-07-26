<?php

/*
|
|--------------------------------------------------
| Simple class autoloader - Scal
|--------------------------------------------------
|
| This is the entry point of Scal
| The including parts and the constant declaration
| To costumize see Scal.json or create and specify it manually
| If you want to develop it, see the Loader.php
|
*/



// Define constants that needed to Scal working
if (!defined('APP_BASE_PATH')) {
    define('APP_BASE_PATH', realpath(''));
}

if (!defined('NAMESPACE_SEPARATOR')) {
    define('NAMESPACE_SEPARATOR', '\\');
}

if (!defined('SCAL_ROOT_PATH')) {
    define('SCAL_ROOT_PATH', __DIR__);
}

if (!defined('SCAL_SUPPORT_PATH')) {
    define('SCAL_SUPPORT_PATH', SCAL_ROOT_PATH . DIRECTORY_SEPARATOR . 'Support');
}

if (!defined('SCAL_TESTS_ENABLED')) {
    define('SCAL_TESTS_ENABLED', false);
}



// Dev mode
if (SCAL_TESTS_ENABLED) {
    require_once(SCAL_SUPPORT_PATH . DIRECTORY_SEPARATOR . 'Test.php');
}


// Support
require_once SCAL_SUPPORT_PATH . DIRECTORY_SEPARATOR . 'Path.php';



// Register Loader
require_once SCAL_ROOT_PATH . DIRECTORY_SEPARATOR . 'Loader.php';
spl_autoload_register('Scal\Loader::load');
