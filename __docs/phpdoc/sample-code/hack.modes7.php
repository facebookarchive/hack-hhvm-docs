<?hh

function foo(): int {
  // UNSAFE
  return "Hi";
}

function bar(): string {
  // UNSAFE
  return 3;
}
