<?hh // strict

newtype EvenInt as int = int;
newtype OddInt = int;

function checkIfEven(int $x): EvenInt {
  if ($x % 2 === 0) {
    return $x;
  }
  throw new Exception("Not Even");
}

function checkIfOdd(int $x): OddInt {
  if ($x % 2 === 1) {
    return $x;
  }
  throw new Exception("Not Odd");
}

function add(int $x, int $y): int {
  return $x + $y;
}
