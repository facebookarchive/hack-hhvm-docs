<?hh

function run_callback((function (string): void) $f, string $s) {
  $f($s);
}

function test2(): void {
  // Although this works at runtime, the type checker will reject this, since an
  // array is not a function.
  run_callback(array('C', 'f'), 'test');

  // But this is acceptable:
  run_callback(class_meth('C', 'f'), 'test');
}
