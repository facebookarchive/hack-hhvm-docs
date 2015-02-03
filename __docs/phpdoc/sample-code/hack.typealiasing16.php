<?hh

// File: type_aliasing_2a.php

newtype UTF8_string = string;

function ret_utf8(string $s): UTF8_string {
  return mb_convert_encoding($s, "UTF8");
}
