// The filter() method can be used on any collection to
// get a concrete collection (usually the same type as
// the original) containing the values that meet some
// condition.
function main() {
  $m = Map {'a' => 1, 'b' => 2, 'c' => 3, 'd' => 4};
  $m2 = $m->filter(function(int $x):bool { return $x % 2 == 0; });
  var_dump($m2);
}

main();
