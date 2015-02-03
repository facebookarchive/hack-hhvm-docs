<?hh
function sum(mixed $x): int {
  if (is_array($x) || $x instanceof Vector) {
    $s = 0;
    foreach ($x as $v) {
      $s += $v;
    }
    return $s;
  }
  //... do something else or throw an exception...
}
