<?hh
class Person {
  public function __construct(public string $name,
                              protected int $age,
                              private bool $gender) {}
}
