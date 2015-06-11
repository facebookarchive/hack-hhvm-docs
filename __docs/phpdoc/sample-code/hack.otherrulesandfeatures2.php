<?hh

function sum(...): float {
  $s = 0;
  foreach (func_get_args() as $e) {
    $s += $e;
  }
  return $s;
}

function main_vna() {
  $x = sum(3, 2, 5, 3, 1.3);
  echo $x; //$x = 14.3
}

main_vna();
