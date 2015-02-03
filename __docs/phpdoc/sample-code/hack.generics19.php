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
  public function foo(): bool {
    return true;
  }
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

// class BoxOfA<T> extends Box<A>
class BoxOfA<T as A> extends Box<T> {
  private int $sum = 0;
  public function __construct(T $e) {
    parent::__construct($e);
    $this->sum += $e->getVal();
  }
}

function main_con(): void {
  $b = new B();
  var_dump($b);
  $box = new BoxOfA($b);
  var_dump($box);
  $b2 = $box->get();
  var_dump($b2);
  var_dump($b2->foo());
}

main_con();
