<?hh

function main(array $x): void {
  $y = new Vector($x);
  var_dump($y);
}

main(array("a", "b", "c"));
