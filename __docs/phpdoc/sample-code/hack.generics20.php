<?hh
class MyClass<+T> {
  public function __construct(T $t) {}
}
class A {}
class B extends A {}

function foo(MyClass<A> $x): void {}

function bar(): void {
  $b = new B();
  $x = new MyClass($b);
  foo($x);
}
