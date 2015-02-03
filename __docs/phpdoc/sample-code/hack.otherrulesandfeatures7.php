<?hh

class Foo {}
class Bar extends Foo { public function blah(): void {} }

function foo(Foo $x): void {
  if ($x instanceof Bar) {
    // $x is now a Bar
    $x->blah();
    ...
  } else {
    // $x is still just a Foo
    ...
  }
}
