<?hh
// Generators implement the Iterator interface;
// a generator can be passed anywhere where an
// Iterator is expected.
function foo(Iterator<int> $it) { .. }
function g() { yield 1; yield 2; }

function main() {
  $gen = g();
  foo($gen);
}

main();
