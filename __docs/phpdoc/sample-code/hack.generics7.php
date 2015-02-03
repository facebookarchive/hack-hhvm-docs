<?hh

class FooGenMethod {
  public function swap<T>(Box<T> $a, Box<T> $b) : T {
    $temp = $a->value;
    $a->value = $b->value;
    $b->value = $temp;
    return $temp;
  }
}
