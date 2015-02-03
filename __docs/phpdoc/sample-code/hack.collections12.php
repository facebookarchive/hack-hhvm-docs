<?hh

// This example creates collections using literal syntax and
// stores these collections in normal local variables
function f() {
  $vec = Vector {1, 2, 3};
  var_dump($vec);
  $map = Map {42 => 'foo', 73 => 'bar', 144 => 'baz'};
  var_dump($map);
}

f();
  