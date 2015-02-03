<?hh

require_once "File1.php";

class UseTypeDefsConv {
  protected Seconds $s = 0;

  public function foo(): void {
    $this->s = 464;
    $m = TypeDefsConv::convertSecondsToMinutes($this->s);
    echo $m;
    TypeDefsConv::funcForMinutes($m);
  }
}

function main_tdc(): void {
  $utdc = new UseTypeDefsConv();
  $utdc->foo();
}

main_tdc();
