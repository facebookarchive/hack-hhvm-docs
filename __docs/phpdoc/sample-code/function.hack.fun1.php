<?hh

function f(string $s): void {}

function test(): void {
  // The type checker cannot determine what function $f refers to. It will reject
  // this in strict mode; partial mode will reject it, but with an error that may
  // not be as helpful (see first type checker error below). This fails at runtime.
  $f = 'f';
  $f(1);

  // But, with a call to fun(), we can know what function $f refers to, and
  // check its arguments statically. This will produce a more valuable type
  // error (as shown in the second type checker error below).
  $f = fun('f');
  $f(1);
}
