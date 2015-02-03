<?hh

function foo<T>(T $a): void {
  echo "foo<T>()";
}
function bar<T>(T $a): int {
  echo "bar<T>()";
  return $a;
}

function main_oct() {
  $x = 5;
  foo($x);
  bar($x);
}

main_oct();
