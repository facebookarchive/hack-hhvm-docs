<?hh

function run_callback((function(string): void) $f, string $s): void {
  $f($s);
}

function test2(): void {
  // Although this works at runtime, the type checker will reject this, since 'f'
  // is a string, not a function.
  run_callback('f', 'test');

  // But this is acceptable:
  run_callback(fun('f'), 'test');
}
