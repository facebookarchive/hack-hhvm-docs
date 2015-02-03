<?hh

// File: type_aliasing_2.php

require_once "type_aliasing_2a.php";
require_once "type_aliasing_2b.php";

function main(): UTF8_string {
  $s = "Hello";
  $o = ret_utf8($s) . ret_utf16($s);
  return $o;
}

main();
