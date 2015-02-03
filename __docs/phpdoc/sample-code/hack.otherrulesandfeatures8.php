<?hh

class Foo {
  public function f1(?int $x): void {
    if (is_int($x)) {
      $this->doSomething();
      $y = $x * 2;
    }
  }

  public function doSomething(): void {}
}
