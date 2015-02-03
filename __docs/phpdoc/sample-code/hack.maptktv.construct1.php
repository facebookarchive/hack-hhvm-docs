<?hh

function main(): void {
  $x = array("a"=>1, "b"=>2, "c"=>3);
  $y = new Map($x);
  var_dump($y);
}

main();
