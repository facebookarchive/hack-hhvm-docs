<?hh

class Foo {
  protected ?int $x;

  public function f1(): void {
    $x = $this->x;
    if (is_int($x)) {
      $this->doSomething();
      $y = $x * 2;
    }
  }

  public function doSomething(): void {}
  }
