<?hh

class NullableNullThrows {
  public function foo(int $y): ?int {
    if ($y > 4) {
      return $y + 5;
    } else {
      return null;
    }
  }
}

class NullableNullThrowsTest {
  protected int $x;

  public function __construct() {
    $nnt = new NullableNullThrows();
    $nullable_value = $nnt->foo(2);
    $this->x = nullthrows($nullable_value);
  }
}

function main_nnt() {
  $nntt = new NullableNullThrowsTest();
}

main_nnt();
