<?hh

class BizBang {
  public function get(): int {
    return time();
  }
}

class NullMemVar {
  private ?BizBang $x;
  public function foo(): BizBang {
    if ($this->x !== null) {
      $a = $this->x->get();
      $b = $this->x->get();
      if ($b - $a < 1) {
        return $this->x;
      }
    }
    return new BizBang();
  }
}

function main_nmv() {
  $c = new NullMemVar();
  var_dump($c->foo());
}

main_nmv();
