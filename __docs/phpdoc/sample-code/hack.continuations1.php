<?hh
function yieldInfiniteInts(): Continuation<int> {
  $i = 0;
  while (true) {
    yield $i++;
  }
}

$generator = yieldInfiniteInts();
foreach ($generator as $value) {
  echo "$value\n";
}
