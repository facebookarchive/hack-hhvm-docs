<?hh
$foo = () ==> 73;
$foo(); // returns 73

$bar = ($x,$y) ==> $x + $y;
var_dump($bar(3,8)); // outputs 11
