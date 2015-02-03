<?hh
// Example 1
function foo() {
  $x = 'bar';
  return function ($y) use ($x) {
    return $x . $y;
  };
}
$fn = foo();
echo $fn('baz'); // Outputs barbaz
