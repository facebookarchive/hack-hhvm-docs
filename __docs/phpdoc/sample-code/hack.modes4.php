<?hh // decl

// Partial mode would catch this. Decl, though, allows // strict
// to call into this.
function bar_partial($a, $b): int {
  return "Hello";
}
