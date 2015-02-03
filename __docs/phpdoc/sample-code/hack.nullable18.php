<?hh

function foo(int $x): bool {
  if ($x > 4) {
    return false;
  }
  return true;
}

function bar(): bool {
  return foo(5);
  // In the past, you might have had to do this check
  //$x = foo(5);
  //if ($x !== null) {
  //  return $x;
  //} else {
  //  throw;
  //}
}
