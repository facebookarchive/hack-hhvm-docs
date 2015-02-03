<?hh

function main() {
  // Collections are objects.
  $v = Vector {1, 2, 3};
  echo is_array($v) ? '$v is an array' : '$v is not an array';
  echo "\n";
  echo is_object($v) ? '$v is an object' : '$v is not an object';
  echo "\n";
  var_dump($v);
}

main();
