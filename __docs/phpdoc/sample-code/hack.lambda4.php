<?hh
// Example 2
function foo(): (function(string): string){
  $x = 'bar';
  return $y ==> $x . $y;
}
$fn = foo();
echo $fn('baz'); // Outputs barbaz
