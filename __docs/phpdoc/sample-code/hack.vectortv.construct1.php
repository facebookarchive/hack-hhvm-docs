<?hh

function main(): void {
  $x = array("a", "b", "c");
  $y = new Vector($x);
  var_dump($y);
}

main();
