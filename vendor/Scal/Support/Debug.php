<?php

/*
|
|--------------------------------------------------
| Class for debugging
|--------------------------------------------------
|
*/

namespace Scal\Support;

class Debug {
  /**
   * Prints human-readable information about a variable and stop the script
   *
   * @return void
   */
  public static function log(...$vars): void
  {
    print_r($vars);
    exit;
  }
  /**
   * Prints human-readable information about a variable
   *
   * @return void
   */
  public static function print(...$vars): void
  {
    print_r($vars);
  }

  /**
   * Dumps information about a variable and stop the script
   *
   * @return void
   */
  public static function type($var): void
  {
    var_dump($var);
    exit;
  }
}
