<?hh

// Testing generic methods in a non-generic class.

class Box<T> {
  public T $value;
  public function __construct(T $v) {
    $this->value = $v;
  }
}

class FooGenMethod {
  public function swap<T>(Box<T> $a, Box<T> $b) : void {
    $temp = $a->value;
    $a->value = $b->value;
    $b->value = $temp;
  }
}

function main_genmeth() {
  $f = new FooGenMethod();
  $y = new Box(3);
  $z = new Box(4);
  echo $y->value." ".$z->value;
  $f->swap($y, $z);
  echo $y->value." ".$z->value;
}

main_genmeth();
