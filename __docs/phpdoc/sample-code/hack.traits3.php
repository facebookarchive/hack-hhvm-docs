<?hh
trait TTT {
  abstract protected function bar(): int;

  protected function foo(): int {
    return $this->bar();
  }
}

class AAA {
  protected function bar(): int {
    return 5;
  }
}

class BBB extends AAA {
  use TTT;

  public function baz(): int {
    return $this->foo();
  }
}

function main_t(): void {
  $bbb = new BBB();
  var_dump($bbb->baz());
}

main_t();
