<?hh

class NullableTest {
  public function mayReturnNull(int $x): ?int {
    if ($x > 5) {
      return 5;
    } else {
      return null;
    }
  }
}

function main_nt() {
  $nt = new NullableTest();
  $y = $nt->mayReturnNull(10);
  var_dump($y);
  $y = $nt->mayReturnNull(4);
  var_dump($y);
}

main_nt();
