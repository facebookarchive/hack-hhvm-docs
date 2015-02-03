<?hh
// A concrete collection can be built from an
// Iterable or KeyedIterable by passing it to a
// collection constructor.
function main() {
  $m = Map {'a' => 11, 'b' => 22, 'c' => 33};
  $v = new Vector($m);
  var_dump($v);
}

main();
