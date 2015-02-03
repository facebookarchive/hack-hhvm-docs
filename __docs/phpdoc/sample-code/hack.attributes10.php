<?hh
<<__ConsistentConstruct>>
class Foo {
  public static function make(): this {
    return new static();
  }
}
class ChildOfFoo extends Foo {
  public function __construct() {}
}

function main() {
  $child = ChildOfFoo::make();
}
main();
