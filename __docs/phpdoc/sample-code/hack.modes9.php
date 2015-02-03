<?hh
function unsafe_foo(int $x, int $y): int {
  if ($x === 3) {
    return 6; // NOT covered by the UNSAFE
  }
  // UNSAFE
  if ($x > $y) {
    return "I am not checked by the type checker"; // Covered by the UNSAFE
  }
  return true; // Covered by the UNSAFE
}
