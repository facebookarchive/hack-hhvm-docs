<?hh
function foo(int $x): mixed {
  if ($x > 4) {
    return "some string";
  } else {
    $div = <div />;
    return $div; // XHPChild
  }
}
