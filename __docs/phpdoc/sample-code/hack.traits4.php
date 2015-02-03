<?hh

interface Bar {
  public function babs(Vector<int> $vec): int;
  public function get(): int;
}

trait Foo implements Bar {
  private int $x = 5;

  private function getVal(): int {
    return $this->x;
  }

  public function get(): int {
    return $this->getVal();
  }

  public function babs(Vector<int> $vec): int {
    if (count($vec) > 0) {
      return $vec[0];
    }
    return -1;
  }
}

class Baz implements Bar {
  use Foo;

  private Vector<int> $v;

  public function __construct() {
    $this->v = Vector {};
    $this->v[] = $this->get();
  }

  public function sass(): int {
    return $this->babs($this->v);
  }
}

function main_traits() {
  $b = new Baz();
  var_dump($b->sass());
}

main_traits();
