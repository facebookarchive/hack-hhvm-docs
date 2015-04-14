<?hh

function gen(): Generator<string, int, void> {
  yield "first" => 0;
  yield "second" => 1;
  yield "third" => 2;
}

function foo(): void {
  foreach (gen() as $x) {
    echo $x, "\n";
  }
}
