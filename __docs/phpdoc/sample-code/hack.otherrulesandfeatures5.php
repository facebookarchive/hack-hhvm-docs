<?hh

function f1(float $x) {
}

function f2(int $x, float $y): int {
  $a = 2 * $x; // $a is an int
  $b = 2 * $y; // $b is a float
  $c = $x / $a; // $c is a num
  $d = $c * $y; // $d is a float
  f1($d);  // this is allowed
  return (int) $c;  // $c needs to be cast here
}
