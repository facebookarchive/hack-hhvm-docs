<?hh

class C {
  public function f(string $s) {}
}

function test(): void {
  $c = new C();

  // The type checker cannot determine what function $f refers to. It will reject
  // this in strict mode, but in partial mode will admit it, even though it
  // fails at runtime.
  $f = array($c, 'f');
  $f(1);

  // But, with a call to inst_meth(), we can know what function $f refers to,
  // and check its arguments statically. This will produce a type error (as
  // shown below)
  $f = inst_meth($c, 'f');
  $f(1);
}
