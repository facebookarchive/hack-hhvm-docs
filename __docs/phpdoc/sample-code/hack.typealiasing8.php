<?hh
// File2.php

require_once "File1.php";

function try_modify_secret_id(SecretID $sid): SecretID {
    return $sid + time() + 2000;
}

function main_ot2(): void {
  try_modify_secret_id(44596);
}

main_ot2();
