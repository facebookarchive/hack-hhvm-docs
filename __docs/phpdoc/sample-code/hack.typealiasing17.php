<?hh

// File: type_aliasing_2b.php

newtype UTF16_string = string;

function ret_utf16(string $s): UTF16_string {
  return mb_convert_encoding($s, "UTF16");
}
