<?hh

function main() {
  $m = Map {1 => 'a', 2 => 'b', 3 => 'c', 4 => 'd'};
  var_dump($m->filterWithKey(function($k, $v) { return $k >= 3; }));
}

main();
