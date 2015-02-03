<?hh
// Using "$c[$k]" syntax or using the at() method
// will throw an exception if the specified key is
// not present. Using the get() method will return
// NULL if the specified key is not present.
function main() {
  $m = Vector {};
  try {
    var_dump($m[0]);
  } catch (OutOfBoundsException $e) {
    echo "Caught exception 1\n";
  }

  try {
    var_dump($m->at(0));
  } catch (OutOfBoundsException $e) {
    echo "Caught exception 2\n";
  }

  try {
    var_dump($m->get(0));
  } catch (OutOfBoundsException $e) {
    echo "Caught exception 3\n";
  }
}

main();
