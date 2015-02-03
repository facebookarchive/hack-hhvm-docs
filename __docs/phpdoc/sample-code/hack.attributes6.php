<?hh
class Baz {}
class Foo {
  <<__Memoize>>
  public function bar(string $key): Baz {
    return function_that_returns_baz($key);
  }
}
