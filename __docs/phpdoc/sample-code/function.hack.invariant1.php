<?hh

function int_not_null(?int $x): int {
  invariant($x !== null, 'Cannot work with a null int');

  // After the invariant call, we know $x cannot be null, so this is fine:
  return $x;
}
