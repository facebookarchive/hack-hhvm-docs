<?hh
function gen(): Continuation<int> {
  yield 1;
  yield 2;
  yield 3;
}

function foo(): void {
  foreach (gen() as $x) {
    echo $x, "\n";
  }
}
