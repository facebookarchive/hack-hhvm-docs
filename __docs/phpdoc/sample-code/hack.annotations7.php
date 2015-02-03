<?hh

// The nullable type is used to indicate that a parameter can be null.
// It is also useful as a return type, where the error case returns null.
// The type checker will force you to handle the null case explicitly.

function f1(int $x): ?string {
  if ($x == 0) {
    return null;
  }
  return "hi";
}

function f2(int $x): void {
  $y = f1($x);
  // $y here has a type of ?string
  if ($y !== null) {
    // $y can be used as an string. No casts required.
  }
}
