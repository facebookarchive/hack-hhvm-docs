<?hh

class C {
  public static function f(string $s) {}
}

function test(): void {
  // The type checker cannot determine what function $f refers to. It will reject
  // this in strict mode, but in partial mode will admit it, even though it
  // fails at runtime.
  $f = array('C', 'f');
  $f(1);

  // But, with a call to class_meth(), we can know what function $f refers to,
  // and check its arguments statically. This will produce a type error.
  $f = class_meth('C', 'f');
  $f(1);
}
