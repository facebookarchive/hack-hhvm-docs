<?hh

function run_callback((function (C): string) $f, C $c) {
  return $f($c);
}

function test2(): void {
  $c = new C();

  // A more realistic example, where meth_caller can be used to generate a
  // callback that can be passed around with full type information.
  $f = meth_caller('C', 'f');
  echo run_callback($f, $c); // Prints "hi test"
}
