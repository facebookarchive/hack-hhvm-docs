<?hh
// All Iterables support map() and filter() to support
// chaining.

function main() {
  $m = Map {'a' => 1, 'b' => 2, 'c' => 3, 'd' => 4};
  $result = $m->filter(function(int $x):bool { return $x % 2 == 0; })
              ->map(function(int $x):int { return $x + 1; });
  foreach ($result as $key => $value) {
    echo $key . " => " . $value . "\n";
  }
}

main();
