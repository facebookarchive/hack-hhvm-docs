<?hh

// Annotated and type checked.
function foo_partial(int $x, int $y): int {
  if ($x > $y) {
    return 27;
  }
  return 34;
}

// Not annotated. Not type checked.
function bar_partial($a, $b) {
  return $a + $b;
}
