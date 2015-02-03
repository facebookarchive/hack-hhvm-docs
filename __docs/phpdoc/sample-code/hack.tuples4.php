<?hh
function test_tup() {
  $tup = tuple(1, 3, 5, 7);
  $tup[2] = 6;
  $tup[4] = 9; // Hack type error since cannot add to tuple
}
