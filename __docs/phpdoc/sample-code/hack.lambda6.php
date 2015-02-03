<?hh
// Example 4
$f = $x ==> $y ==> $x + $y;
$g = $f(7);
echo $g(4); // Outputs 11
