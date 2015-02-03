<?hh
// Use Indexish to be able to pass Map or Vector, in addition
// to arrays, to this function and return an array like before. Notice how
// foreach and bracket syntax is being used.
function array_compose<T1, T2, T3>(
  Indexish<T1, T2> $f,
  Indexish<T2, T3> $g
): array<T1, T3> {
  $ret = array();
  foreach ($f as $x => $y) {
    if (array_key_exists($y, $g)) {
      $ret[$x] = $g[$y];
    }
  }
  return $ret;
}
  