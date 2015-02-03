<?hh

class Foo {
  protected ?int $x;

  public function f1(): void {
    if (is_int($this->x)) {
      $this->doSomething();
      // can no longer assume $this->x is an int, doSomething might have changed it back to null.
      // note: can't analyze doSomething() because a child class of Foo might change its behavior.
      $y = $this->x * 2;
    }
  }

  public function doSomething(): void {}
}
