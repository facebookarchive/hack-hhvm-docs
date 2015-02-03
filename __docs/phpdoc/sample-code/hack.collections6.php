<?hh

function main() {
  // Create a Map using collection literal syntax
  $map = Map {"A" => 1, "B" => 2, "C" => 3};

  // Add elements using "$c[$k]=$v" syntax; note that if $k is
  // already present, "$c[$k]=$v" syntax will overwrite the previous
  // value
  $map["D"] = 4;
  $map["E"] = 5;

  // Access value by key using "$c[$k]" syntax; note that "$c[$k]"
  // syntax will throw an OutOfBoundsException if the key is not present
  echo $map["A"] . "\n";

  // Access value by key via get(); null will be returned if the key is
  // not present
  echo $map->get("B") . "\n\n";

  // Remove element by key; if the key is not present the remove()
  // method will do nothing and return
  $map->remove("B");

  // Testing if a key is present
  echo ($map->contains("A") ? "true" : "false") . "\n\n";

  // Iterate over the values using "foreach ($c as $v)" syntax
  foreach ($map as $v) {
    echo $v . "\n";
  }

  // Iterate over the keys and values using "foreach ($c as $k=>$v)"
  // syntax
  foreach ($map as $k => $v) {
    echo $k . ": " . $v . "\n";
  }
}

main(); // REMEMBER, insertion order is maintained.
