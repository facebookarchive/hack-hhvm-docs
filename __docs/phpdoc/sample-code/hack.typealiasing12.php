<?hh // strict

require_once "ot1.php";

function test_me(): void {
  add(checkIfEven(4), checkIfEven(6));
  add(checkIfOdd(3), checkIfOdd(5));
}
