<?hh
class MyClass {
  const int MyConst = 0;

  private string $x = '';

  public function increment(int $x): int {
    $y = $x + 1;
    return $y;
  }
}
