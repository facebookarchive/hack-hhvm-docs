<?hh
$z = 11;
$foo = $x ==> $y ==> $x * $z + $y;
$bar = $foo(5);
var_dump($bar(4)); // outputs 59
