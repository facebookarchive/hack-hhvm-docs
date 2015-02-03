<?hh
// The map() method can be used on any collection to
// get a concrete collection (usually the same type as
// the original) containing each value with some
// operation applied.
function main() {
  $m = Map {'a' => 1, 'b' => 2, 'c' => 3, 'd' => 4};
  $m2 = $m->map(function(int $x):int { return $x + 10; });
  var_dump($m2);
}

main();
