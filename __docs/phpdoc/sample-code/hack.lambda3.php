<?hh
// Never do this, but just illustrating that this won't be captured.
$b = 34;
function lam(int $b): int {
  // Multi-statement lambda body
  // $b is captured from the parameter in this function body
  $fn = $a ==> {$b = mult($b); return ($a + $b);};
  // Do other stuff
  // .....
  return $fn(10);
}

function mult(int $x): int {
  return $x * 4;
}
var_dump(lam(42)); // return 178
