<?hh
require_once "File1.php";

function not_so_secrets_are_ints(NotSoSecret $x, NotSoSecret $y): NotSoSecret {
  return $x + $y;
}

function main_ta(): void {
  echo not_so_secrets_are_ints(4, 5);
}

main_ta();
