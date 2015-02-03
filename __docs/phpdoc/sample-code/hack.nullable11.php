<?hh
class NullableTest {
  public function mayReturnNull(int $x): ?int {
    if ($x > 5) {
      return 5;
    } else {
      return null;
    }
  }

  public function nullableParameter(int $x): int {
    if (is_null($x)) {
      return 100;
    } else {
      return -1;
    }
  }
}

function main_nt() {
  $nt = new NullableTest();
  $y = $nt->mayReturnNull(10);
  var_dump($y);
  $y = $nt->mayReturnNull(4);
  var_dump($y);

  $z = $nt->nullableParameter(10);
  var_dump($z);
  $z = $nt->nullableParameter(null);
  var_dump($z);
}

main_nt();
