<?hh
class Foo {
  protected int $x;

  public function __construct() {
    $this->x = 10;
    $this->foo();
  }
  protected function foo() {
    ...
  }
}
