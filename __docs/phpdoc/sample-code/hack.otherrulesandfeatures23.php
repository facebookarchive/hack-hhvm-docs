<?hh

class Foo {}
class FooChild extends Foo {}

class AA {
  protected function bar(): Foo { return new Foo(); }
}

class BB extends AA {
  protected function bar(): int { return 1; }
}
