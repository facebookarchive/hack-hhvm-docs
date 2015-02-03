<?hh
class MyClass<-T> {
  public function __construct(T $t) {}
}
class A {}
class B extends A {}

function foo(MyClass<B> $x): void {}

function bar(): void {
  $a = new A();
  $x = new MyClass($a);
  foo($x);
}
