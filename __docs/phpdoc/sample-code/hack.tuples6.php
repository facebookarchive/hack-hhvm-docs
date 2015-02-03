<?hh

class ReturnMultipleValues {

  public function foo(): array<mixed> {
    $arr = array("Hello", 3);
    $arr[2] = 4;
    var_dump($arr);
    return $arr;
  }

  public function bar(): Vector<mixed> {
    $vec = Vector {"Hello", 3};
    $vec->add(4);
    var_dump($vec);
    return $vec;
  }

  // This is how a tuple is returned from a method
  public function baz(): (string, int) {
    $tup = tuple("Hello", 3);
    //$tup[2] = 4;
    return $tup;
  }
}

function main_tup() {
  $rmv = new ReturnMultipleValues();
  $rmv->foo();
  $rmv->bar();
  $rmv->baz();
}

main_tup();
