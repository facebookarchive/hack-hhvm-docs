<?hh

class Foo<T> {

  public function getOpen(Vector<T> $vec): T {
      return $vec[0];
  }

  public function getClosed(Vector<int> $vec): int {
      return $vec[0];
  }
}
