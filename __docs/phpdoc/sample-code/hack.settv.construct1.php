<?hh

function main(): void {
  $x = array("a", "b", "c");
  $y = new Set($x);
  var_dump($y);
}

main();
