<?hh

type Matrix<T> = Vector<Vector<T>>;

function bar_ta_gen<T>(Matrix<T> $x): void {
  var_dump($x);
}
