<?hh
function check_not_null(?int $x): int {
  if ($x === null) {
    return -1;
  } else {
    return $x;
  }
}
