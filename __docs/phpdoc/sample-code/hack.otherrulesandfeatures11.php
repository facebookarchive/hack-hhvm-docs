<?hh

abstract class A {
  protected int $x;
}

class B extends A {
  public function __construct() {
    $this->x = 10;
  }
}
