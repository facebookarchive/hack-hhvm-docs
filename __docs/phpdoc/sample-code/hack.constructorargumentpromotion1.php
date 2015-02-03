<?hh

class Person {
  private string $name;
  private int $age;

  public function __construct(string $name, int $age) {
    $this->name = $name;
    $this->age = $age;
  }
}
