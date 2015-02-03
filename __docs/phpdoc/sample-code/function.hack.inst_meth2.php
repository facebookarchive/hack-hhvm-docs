<?hh

function run_callback((function (string): void) $f, string $s) {
  $f($s);
}

function test2(): void {
  $c = new C();

  // Although this works at runtime, the type checker will reject this, since an
  // array is not a function.
  run_callback(array($c, 'f'), 'test');

  // But this is acceptable:
  run_callback(inst_meth($c, 'f'), 'test');
}
