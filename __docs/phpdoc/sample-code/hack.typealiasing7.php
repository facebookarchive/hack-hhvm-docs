<?hh
// File1.php

newtype SecretID = int;

function modify_secret_id(SecretID $sid): SecretID {
  return $sid - time() - 2042;
}

function main_ot1(): void {
  echo modify_secret_id(44596);
}

main_ot1();
