<?hh

function main() {
  // Create a Vector using collection literal syntax
  $vector = Vector {5, 10, 15};

  // Add elements using "$c[]=$v" syntax
  $vector[] = 20;
  $vector[] = 25;

  // Access value by key using "$c[$k]" syntax; note that "$c[$k]"
  // syntax will throw an OutOfBoundsException if the key is out of bounds
  echo $vector[0] . "\n";

  // Access value by key using get(); null will be returned if the key is
  // out of bounds
  echo $vector->get(1) . "\n\n";

  // Set value by key using "$c[$k]=$v" syntax, overwriting the previous
  // value; note that "$c[$k]=$v" syntax for Vectors will throw an
  // OutOfBoundsException if the key is out of bounds
  $vector[0] = 999;

  // Remove an element by key
  $vector->removeKey(2);

  // Iterate over the values using "foreach ($c as $v)" syntax
  foreach ($vector as $v) {
    echo $v . "\n";
  }
  echo "\n";

  // Iterate over the values using "for" and "$c[$x]" syntax
  for ($i = 0; $i < count($vector); ++$i) {
    echo $vector[$i] . "\n";
  }

  // Iterate over the keys and values using "foreach ($c as $k=>$v)"
  // syntax
  foreach ($vector as $k => $v) {
    echo $k . ": " . $v . "\n";
  }
  echo "\n";
}

main();
