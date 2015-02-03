<?hh

class IMVChild extends InitializeMemberVariables {
  protected ?int $i;

  // Parent constructor called, setup called from there and dispatched to here
  // to initialize member variables
  protected function setup(int $i) {
    $this->i = $i;
    var_dump($this);
  }
}

class IMVChild2 extends InitializeMemberVariables {

  protected string $s;
  protected bool $b;
  protected int $i;

  // Parent constructor called, setup called from there and dispatched to here
  // to initialize member variables
  protected function setup(string $s, bool $b, int $i) {
    $this->s = $s;
    $this->b = $b;
    $this->i = $i;
    var_dump($this);
  }

}

$imvc = new IMVChild(4);
$imvc2 = new IMVChild2("Hi", true, 3);
