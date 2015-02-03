<?hh
$foo = $x ==> $x + 1;
$foo(12); // returns 13

$squared = array_map($x ==> $x*$x, array(1,2,3));
var_dump($squared); // $squared is array(1,4,9)
