<?hh

function main(): void {
  $x = array("a", "b", "c");
  $y = new ImmSet($x);
  var_dump($y);
}

main();
