<?hh
function foo(): bool {
  $foo = 10;   // $foo is an integer
  $bar = (bool) $foo;   // $bar is a boolean
  return $bar;
}

foo();
