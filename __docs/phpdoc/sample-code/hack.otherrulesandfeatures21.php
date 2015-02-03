<?hh

function foo(string $var): void {
  echo $var."\n";
}

function main(): void {
  $name='foo';
  $func1 = fun($name); // the Hack type checker would balk
  $func1('bar');

  $func2 = fun('foo'); // This is correct
  $func2('biz');

  // func2('baz'); //this will not work
}

main();
