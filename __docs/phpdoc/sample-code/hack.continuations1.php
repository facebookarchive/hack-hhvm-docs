<?hh
function yieldInfiniteInts(): Generator<int, int, void> {
  $i = 0;
  while (true) {
    yield $i++;
  }
}

$generator = yieldInfiniteInts();
foreach ($generator as $value) {
  echo "$value\n";
}
