<?hh

require_once "File1.php";

function foo_ta_gen(Vector<Vector<float>> $x): void {
  bar_ta_gen($x); // Vector<Vector<float>> is identical to Matrix<float>
}

function foo_ta_gen_main(): void {
  $vecvec = Vector {Vector {1.0, 2.0}, Vector {3.0, 4.0}};
  foo_ta_gen($vecvec);
}

foo_ta_gen_main();
