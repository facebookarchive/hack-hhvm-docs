<?hh
// Use Indexish to be able to pass Map or Vector in addition
// to arrays, to this function and return a Map, which is what this
// function is actually returning in practice. Notice how
// foreach and bracket syntax is being used.
function map_compose<T1, T2, T3>(
  Indexish<T1, T2> $f,
  Indexish<T2, T3> $g
): Map<T1, T3> {
  $ret = Map {};
  foreach ($f as $x => $y) {
    if (array_key_exists($y, $g)) {
      $ret[$x] = $g[$y];
    }
  }
  return $ret;
}
