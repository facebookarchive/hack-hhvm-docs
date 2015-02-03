<?hh

class A {
  public function getVal(): int {
    return 1;
  }
}

class B extends A {
  public function getVal(): int {
    return 2;
  }
  public function foo(): void {}
}

class Box<T> {
  private T $data;
  public function __construct(T $x) {
    $this->data = $x;
  }
  public function get(): T {
    return $this->data;
  }
}

class BoxOfA<T> extends Box<A> {
  private int $sum = 0;
  public function __construct(T $e) {
    parent::__construct($e);
    $this->sum += $e->getVal();
  }
}

function main_con(): void {
  $b = new B();
  $box = new BoxOfA($b);
  $b2 = $box->get();
  $b2->foo();
}
