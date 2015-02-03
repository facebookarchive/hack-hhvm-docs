<?hh
// Like all objects, collections has "reference-like"
// semantics for assignment, parameter passing, and
// foreach.
function foo($v) {
  $v[1] = 7;
}

function main() {
  $v1 = Vector {1, 2, 3};
  $v2 = $v1;
  foo($v2);
  var_dump($v1);
  echo "\n";
  foreach ($v1 as $key => $value) {
    echo $key . " => " . $value . "\n";
    if ($key == 0) {
      $v1[2] = 9;
    }
  }
}

main();
