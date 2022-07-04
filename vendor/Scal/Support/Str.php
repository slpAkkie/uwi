<?php

/*
|
|--------------------------------------------------
| Class for work with strings
|--------------------------------------------------
|
| Support to work with strings
|
*/

namespace Scal\Support;

class Str {
  /**
   * Checks if a string starts with a given substring
   *
   * @param string haystack The string to search in.
   * @param string needle The substring to search for in the haystack.
   * @return bool
   */
  public static function startsWith(string $haystack, string $needle): bool
  {
    return substr($haystack, 0, strlen($needle)) === $needle;
  }

  /**
   * Cut off the beginning from the line
   * If lenght is number then the specified number of characters will be cut off first of the string
   * If it is a string, it will be checked whether the string starts with this substring and if true
   * cut off this piece
   *
   * @param string $from
   * @param string|int $length
   *
   * @return string
   */
  public static function slice(string $from, $length): string
  {
    switch (gettype($length)) {
      case 'string':
        if (self::startsWith($from, $length)) return substr($from, strlen($length));
        else return $from;
      case 'int': return substr($from, $length);
      default: return $from;
    }
  }
}
