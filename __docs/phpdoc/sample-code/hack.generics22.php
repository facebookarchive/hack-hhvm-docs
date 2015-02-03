<?hh
class MyClass<+T> {
  public function __construct(T $t) {}
}
class A {}
class B extends A {}
class C {}

function foo(MyClass<A> $x): void {}

function bar(): void {
  $c = new C();
  $x = new MyClass($c);
  foo($x);
}
