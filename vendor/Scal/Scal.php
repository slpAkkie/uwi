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

// Constants
if (defined('SCAL_PROJECT_ROOT'))
  define('SCAL_EXECUTED_IN', SCAL_PROJECT_ROOT);
else
  define('SCAL_EXECUTED_IN', realpath('') . DIRECTORY_SEPARATOR);

define('SCAL_REAL_PATH', __DIR__ . DIRECTORY_SEPARATOR);
define('SCAL_SUPPORT_PATH', SCAL_REAL_PATH . 'Support' . DIRECTORY_SEPARATOR);
define('SCAL_EXCEPTIONS_PATH', SCAL_REAL_PATH . 'Exceptions' . DIRECTORY_SEPARATOR);
define('SCAL_KERNEL_PATH', SCAL_REAL_PATH . 'Kernel' . DIRECTORY_SEPARATOR);

!defined('SCAL_EXCEPTION_MODE') && define('SCAL_EXCEPTION_MODE', false);
!defined('SCAL_DEV_MODE') && define('SCAL_DEV_MODE', false);



// Dev mode
if (SCAL_DEV_MODE) {

  require_once SCAL_SUPPORT_PATH . 'Debug.php';
  require_once SCAL_SUPPORT_PATH . 'Test.php';
}

// Exception mode
if (SCAL_EXCEPTION_MODE) {
  require_once SCAL_EXCEPTIONS_PATH . 'BaseException.php';
  require_once SCAL_EXCEPTIONS_PATH . 'ClassNotFoundException.php';
  require_once SCAL_EXCEPTIONS_PATH . 'ConfigurationNotFoundException.php';
  require_once SCAL_EXCEPTIONS_PATH . 'FileNotFoundException.php';
  require_once SCAL_EXCEPTIONS_PATH . 'FolderNotFoundException.php';
}


// Support
require_once SCAL_SUPPORT_PATH . 'Path.php';
require_once SCAL_SUPPORT_PATH . 'Str.php';

// Kernel
require_once SCAL_KERNEL_PATH . 'Loader.php';



// Register Loader
spl_autoload_register('Scal\Loader::load');
