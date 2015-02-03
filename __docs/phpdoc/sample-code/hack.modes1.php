<?hh // strict

// Everything is annotated and type checked
class StrictClass {
  private string $str;

  // Need this constructor or the type checker
  // will complain about uninitialized member
  // variables
  public function __construct() {
    $this->str = "Hello";
  }

  public function foo(int $x, int $y): int {
    if ($x < $y) {
      return 27;
    }
    return 34;
  }

  public function bar(string $a, string $b): string {
    return $a . $b;
  }
}
