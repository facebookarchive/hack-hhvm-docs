<?hh

class BizBang {
  public function get(): int {
    return time();
  }
}

class NullMemVar {
  private ?BizBang $x;
  public function foo(): BizBang {
    $local_for_x = $this->x;
    if ($local_for_x !== null) {
      $a = $local_for_x->get();
      $b = $local_for_x->get();
      if ($b - $a < 1) {
        return $local_for_x;
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
