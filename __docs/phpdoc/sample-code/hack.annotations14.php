<?hh

class FooFoo {}
class HackArrayAnnotations {
  private array<FooFoo> $arr;
  private array<string, FooFoo> $arr2;

  public function __construct() {
    $this->arr = array();
    $this->arr2 = array();
  }

  public function bar<T>(T $val): array<T> {
    return array($val);
  }

  public function sort(array<int, float> $a): array<int, float> {
    sort($a);
    return $a;
  }

  public function baz(FooFoo $val): array<FooFoo> {
    $this->arr[] = $val;
    return $this->arr;
  }
}

function main_aa() {
  $haa = new HackArrayAnnotations();
  var_dump($haa->bar(3));
  var_dump($haa->bar(new FooFoo()));
  var_dump($haa->sort(array(1.3, 5.6, 2.3, 0.2, 1.4)));
  var_dump($haa->baz(new FooFoo()));
}

main_aa();
