<?hh
class AnnotatedClass {
  public int $x;
  private string $s;
  protected array $arr;
  public AnotherClass $ac;

  function bar(string $str, bool $b): float {
    if ($b && $str === "Hi") {
       return 3.2;
    }
    return 0.3;
  }
}
