<?hh
class Baz {}
class Foo {
  private Map $cache;

  public function bar(string $key): Baz {
    if (!isset($this->cache[$key])) {
      $this->cache[$key] = function_that_returns_baz($key);
    }
    return $this->cache[$key];
  }
}
