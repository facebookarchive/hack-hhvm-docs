<?hh

function main() {
  // Create a Set using collection literal syntax
  $set = Set {"A", "B"};

  // Add elements using "$c[]=$v" syntax
  $set[] = "C";

  // This will have no effect since "B" exists already
  $set[] = "B";

  // Add elements using the add() method
  $set->add("D")->add("E");

  // Remove element by value
  $set->remove("B");

  // Testing if a value is present
  echo ($set->contains("A") ? "true" : "false") . "\n\n";

  // Iterate over the values using "foreach ($c as $v)" syntax
  foreach ($set as $item) {
    echo $item . "\n";
  }
}

main();
