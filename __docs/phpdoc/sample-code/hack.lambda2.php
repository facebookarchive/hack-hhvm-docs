<?hh
// Never do this, but just illustrating that this won't be captured.
$b = 34;
function lam(int $b): int {
  // $b is captured from the parameter in this function body
  $fn = $a ==> $a + $b;
  // Do other stuff
  // .....
  return $fn(10);
}

var_dump(lam(42)); // return 52
