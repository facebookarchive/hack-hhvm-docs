<?hh

class Foo {}
class FooChild extends Foo {}

class AA {
  protected function bar(): FooChild { return new FooChild(); }
}

class BB extends AA {
  protected function bar(): Foo { return new Foo(); }
}
