<?hh

// File1.php

newtype SecretString = string;

function modify(SecretString $s): SecretString {
  return $s . "1";
}

function main_ot1(): void {
  var_dump(modify("Hello"));
}

main_ot1();
