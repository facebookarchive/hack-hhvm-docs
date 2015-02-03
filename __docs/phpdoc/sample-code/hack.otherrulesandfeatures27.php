<?hh

class Foo {
  public function foo_method(): void {}
}

function mixed_method(int $x): mixed {
  if ($x === 3) {
    $a = array();
    $a[0] = new Foo();
    return $a;
  } else {
    return false;
  }
}

function bar(): bool {
  $untyped_array = mixed_method(3);
  // Let's assume that this method can return an array of Foo
  invariant(is_array($untyped_array), "must be an array of Foo()");
  $untyped_array[0]->foo_method(); // Hack now understands that $untyped_array is an array
  return true;
}

bar();
