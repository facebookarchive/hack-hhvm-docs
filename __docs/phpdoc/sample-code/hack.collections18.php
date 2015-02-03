<?hh
// The "read-only" style interfaces (such as ConstMap)
// can be used to indicate that a function will not
// modify the collection.
function foo(ConstMap<string,int> $m, string $k): int {
  echo $m[$k] . "\n";
}

function main() {
  $m = Map {'a' => 1, 'b' => 2, 'c' => 3, 'd' => 4};
  foo($m, 'c');
}
