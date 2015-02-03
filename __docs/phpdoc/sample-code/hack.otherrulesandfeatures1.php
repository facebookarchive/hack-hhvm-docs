<?hh

function var_foo(int $x, ...) : int {
  // use func_get_args() to get the actual arguments returned as an array
  // Remember, in this case, the $x will be counted as an argument too.
  $arg_arr = func_get_args();
  return $x + count($arg_arr);
}

function main_vna() {
  $y = var_foo(3, 2, 'hi', array(), 1.3); // $y = 8 (3+5)
  $z = var_foo(3); // $z = 4 (3+1)
  echo $y."\n";
  echo $z;
}

main_vna();
