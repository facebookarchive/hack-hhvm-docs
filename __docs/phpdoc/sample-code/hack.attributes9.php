<?hh
<<__ConsistentConstruct>>
class Foo {
  public static function make(): this {
    return new static();
  }
}
class ChildOfFoo extends Foo {
  public function __construct(private A $a) {}
}

function main() {
  $child = ChildOfFoo::make();
}
main();
