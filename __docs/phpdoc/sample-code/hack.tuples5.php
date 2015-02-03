<?hh
// test_tup takes a two-string tuple and returns a Vector of two-string tuples.
function test_tup((string, string) $tup): Vector<(string, string)> {
  $vec = Vector{$tup};
  return $vec;
}
