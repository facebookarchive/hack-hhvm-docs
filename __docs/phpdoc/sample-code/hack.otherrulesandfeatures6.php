<?hh

function foo(int $y): void { }

function bar(?int $x): void {
  if (is_int($x)) {
    foo($x);
  } else {
    ...
  }
}
