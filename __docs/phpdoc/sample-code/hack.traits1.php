<?hh

trait Foo {
  private int $x = 5;
  private bool $b = false;

  protected function getVal(): int {
    return $this->x;
  }

  public function getBin(Vector<bool> $vec): string {
    return $vec[0];
  }
}
