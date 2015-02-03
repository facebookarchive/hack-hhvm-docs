<?hh
// Example 3
function foo(): (function(string): string) {
  $x = 'bar';
  return ($y) ==> { return $x . $y; };
}
$fn = foo();
echo $fn('baz'); // Outputs barbaz
