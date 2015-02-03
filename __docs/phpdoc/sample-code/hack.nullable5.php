<?hh

class NullableBool {
  public function boolMayBeNull(?bool $b): string {
    if ($b === null) {
      return "Null";
    } else if ($b === true) {
      return "True";
    } else {
      return "False";
    }
  }
}

function main_nt() {
  $nb = new NullableBool();
  $x = $nb->boolMayBeNull(true);
  $y = $nb->boolMayBeNull(false);
  $z = $nb->boolMayBeNull(null);
  var_dump($x);
  var_dump($y);
  var_dump($z);
}

main_nt();
