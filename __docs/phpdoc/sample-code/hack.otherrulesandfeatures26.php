<?hh

interface I {
  public function foo();
}

class A implements I {
  public function foo(): void {
    echo "A";
  }
}

class B implements I {
  public function foo(): void {
    echo "B";
  }

  public function yay(): void {
    echo "B->yay!";
  }
}

function baz(int $a): I {
  return $a === 1 ? new A() : new B();
}

function bar(): B {
  $iface = baz(2);
  invariant($iface instanceof B, "must be a B");
  $iface->yay();
  return $iface;
}

bar();
