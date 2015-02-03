<?hh
async function genFoo(): Awaitable<Foo> { return new Foo(); }
async function cached_result<T>(T $x): Awaitable<T> { return $x; }
async function gen_void(): Awaitable<void> { return; }
async function gen_add(Awaitable<int> $genA, Awaitable<int> $genB): Awaitable<int> {
  list($a, $b) = await genva($genA, $genB);
  return $a + $b;
}
class Preparable<T> implements Awaitable<T> { ... }
class MyPreparable extends Preparable<MyPreparable> { ... }
