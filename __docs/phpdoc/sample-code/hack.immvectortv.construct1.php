<?hh

function main(): void {
  $x = array("a", "b", "c");
  $y = new ImmVector($x);
  var_dump($y);
}

main();
