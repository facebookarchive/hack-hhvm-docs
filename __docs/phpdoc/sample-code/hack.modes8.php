<?hh
function unsafe_foo(int $x, int $y): int {
  if ($x > $y) {
    // UNSAFE
    return "I am not checked by the type checker"; // Covered by UNSAFE
  }
  return 34; // NOT covered by UNSAFE
}
