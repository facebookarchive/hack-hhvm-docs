<?hh

function main(): void {
  $x = array("a"=>1, "b"=>2, "c"=>3);
  $y = new ImmMap($x);
  var_dump($y);
}

main();
