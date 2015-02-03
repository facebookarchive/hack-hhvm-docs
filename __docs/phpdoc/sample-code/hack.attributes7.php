<?hh
class Baz {}
class MParent {
  // Memozing will *not* occur on this function
  public function foo(string $key): Baz {
    return function_that_returns_baz($key);
  }
}
class Child extends MParent {
  <<__Override, __Memoize>>
  public function foo(string $key): Baz {
    return another_function_that_returns_baz($key);
  }
}
