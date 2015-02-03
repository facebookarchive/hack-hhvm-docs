<?hh

class BarBar {}

class ABCD {
  private array<BarBar> $arr;
  private int $i;

  public function __construct() {
    $this->arr = array(new BarBar());
    $this->i = 4;
  }
  public function getBars(): array<BarBar> {
    if ($this->i < 5) {
      return array();
    } else if ($this->i < 10) {
      return $this->arr;
    } else {
      return array(null); // Type Error
    }
  }
}
