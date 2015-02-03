<?hh // strict

class A {}
class Z extends A {}

function foo_gi(): void {
  $x = Vector {};
  $x->add(new A());
  $x->add(new Z());
}

function foo_gi2(): void {
  $x = Vector {};
  $x->add(new Z());
  $x->add(new A());
  foo_gi4($x);
}

function foo_gi3(bool $b): void {
  $x = Vector {};
  $x->add(new Z());
  $x->add(new A());
  foo_gi5($x);
}

function foo_gi4(Vector<Z> $vec): void {}
function foo_gi5(Vector<A> $vec): void {}
function foo_gi6(Vector<mixed> $vec): void {}
