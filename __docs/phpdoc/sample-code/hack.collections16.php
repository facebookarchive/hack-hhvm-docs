<?hh
// Certain kinds of modification, such as
// removing an element, will cause iterators to
// be invalidated (including foreach loops).
function main() {
  $m = Map {'a' => 1, 'b' => 2, 'c' => 3, 'd' => 4};
  foreach ($m as $key => $value) {
    echo $key . " => " . $value . "\n";
    if ($key == 'a') {
      $m->remove('d');
    }
  }
}

main();
