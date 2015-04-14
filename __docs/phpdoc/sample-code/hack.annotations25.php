<?hh
function gen(): Generator<int, int, void> {
  yield 1;
  yield 2;
  yield 3;
}

function foo(): void {
  foreach (gen() as $x) {
    echo $x, "\n";
  }
}
