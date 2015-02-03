<?hh
// This function does a relational join on two mappish style arrays into one
// mappish style array
function array_compose($f, $g): array {
  $ret = array();
  foreach ($f as $x => $y) {
    if (array_key_exists($y, $g)) {
      $ret[$x] = $g[$y];
    }
  }
  return $ret;
}
