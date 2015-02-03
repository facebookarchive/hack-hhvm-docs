<?hh

class A {}

function foo(A $x): void {
  ...
}

function sum(Vector<int> $arr): int {
  $s = 0;
  foreach ($arr as $v) {
    $s += $v;
  }
  return $s;
}
