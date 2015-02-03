<?hh
$v1 = Vector {1,2,3};
$v2 = Vector {4,5,6};
$v3 = Vector {7,8,9};
$p = Pair {$v1, $v2};
// $p[1] = $v3; // EXCEPTION HERE
$p[1][2] = 999;
