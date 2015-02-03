<?hh
function foo(): bool {
  $foo = 10;   // $foo is an integer
  $bar = (boolean) $foo;   // $bar is a boolean
  return $bar;
}

foo();
