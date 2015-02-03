<?hh
class FooClass{}

class MyClass {
  T $t;
  FooClass $a
  ?int $ni;
  (function(int, int): string) $x;

  public function __construct() {
    $this->t = $val;
    $this->a = new FooClass();
    $this->ni = $val === 3 ? null : 4;
    $this->x = function(int $n, int $m): string {
      $r = '';
      for ($i=0; $i < $n+$m; $i++) {
        $r .= "hi";
      }
      return $r;
    };
  }
}
