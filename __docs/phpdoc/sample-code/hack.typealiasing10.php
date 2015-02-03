<?hh

// File2.php

require_once "File1.php";

function try_modify(SecretString $s): SecretString {
  return $s . "1";
}

function main_ot2(): void {
    var_dump(modify("Hello"));
}

main_ot2();
