<?hh
function f1(): ?resource {
  // UNSAFE
  return fopen('/dev/null', 'r');
}

function f2(resource $x): void {
}

function f3(): void {
  $x = f1();
  if (is_resource($x)) {
    f2($x);
  }
}
