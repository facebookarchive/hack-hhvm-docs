<?hh
// The keys() method can be used on Vector and Map
// to get a Vector of the keys.
function main() {
  $m = Map {'a' => 1, 'b' => 2, 'c' => 3, 'd' => 4};
  $v = $m->keys();
  var_dump($v);
}

main();
